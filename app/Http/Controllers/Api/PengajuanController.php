<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePengajuanRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\Form3In1;
use App\Models\FormKia;
use App\Models\FormKkPenambahan;
use App\Models\FormKkPerbaikan;
use App\Models\FormKkPengurangan;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class PengajuanController extends Controller
{
    #[OA\Get(
        path: '/pengajuan',
        summary: 'Daftar pengajuan milik warga',
        description: 'Mengembalikan semua pengajuan milik warga yang sedang login, diurutkan terbaru. Mendukung filter berdasarkan status.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'status',
                in: 'query',
                required: false,
                description: 'Filter berdasarkan status pengajuan',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diproses_kecamatan', 'selesai']
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar pengajuan dengan pagination',
                content: new OA\JsonContent(ref: '#/components/schemas/PengajuanListResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $pengajuans = Pengajuan::with('user')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => PengajuanResource::collection($pengajuans),
            'meta' => [
                'current_page' => $pengajuans->currentPage(),
                'last_page'    => $pengajuans->lastPage(),
                'total'        => $pengajuans->total(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/pengajuan',
        summary: 'Buat pengajuan baru',
        description: 'Membuat pengajuan baru dengan jenis layanan yang dipilih. Setelah ini, warga perlu mengisi form detail dan upload dokumen secara terpisah.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['jenis_layanan', 'no_whatsapp'],
                properties: [
                    new OA\Property(
                        property: 'jenis_layanan',
                        type: 'string',
                        enum: ['kia', '3_in_1', 'kk_penambahan', 'kk_pengurangan', 'kk_perbaikan', 'akta_kelahiran', 'akta_kematian'],
                        example: 'kia',
                        description: 'Jenis layanan yang diajukan'
                    ),
                    new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789', description: 'Nomor WhatsApp untuk notifikasi'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pengajuan berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pengajuan berhasil dibuat.'),
                        new OA\Property(property: 'pengajuan', ref: '#/components/schemas/PengajuanResponse'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 422,
                description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function store(StorePengajuanRequest $request): JsonResponse
    {
        $pengajuan = Pengajuan::create([
            'user_id'       => Auth::id(),
            'jenis_layanan' => $request->jenis_layanan,
            'no_whatsapp'   => $request->no_whatsapp,
            'status'        => 'berkas_diterima',
        ]);

        return response()->json([
            'message'   => 'Pengajuan berhasil dibuat.',
            'pengajuan' => new PengajuanResource($pengajuan->load('user')),
        ], 201);
    }

    #[OA\Get(
        path: '/pengajuan/{pengajuan}',
        summary: 'Detail satu pengajuan',
        description: 'Mengembalikan detail lengkap pengajuan termasuk data form, riwayat status, dan informasi pemohon.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'pengajuan', in: 'path', required: true, description: 'ID pengajuan', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail pengajuan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PengajuanResponse'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — bukan pengajuan milik user ini'),
            new OA\Response(response: 404, description: 'Pengajuan tidak ditemukan'),
        ]
    )]
    public function show(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        $pengajuan->load([
            'user',
            'riwayatStatuses',
            'formKia',
            'form3In1',
            'formKkPenambahan',
            'formKkPengurangan',
            'formKkPerbaikan.jenisPerbaikan',
        ]);

        return response()->json([
            'data' => new PengajuanResource($pengajuan),
        ]);
    }

    #[OA\Put(
        path: '/pengajuan/{pengajuan}',
        summary: 'Revisi pengajuan yang ditolak',
        description: 'Hanya bisa dilakukan jika status pengajuan adalah ditolak_desa atau ditolak_kecamatan. Status akan direset ke berkas_diterima untuk review ulang.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'pengajuan', in: 'path', required: true, description: 'ID pengajuan', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['no_whatsapp'],
                properties: [
                    new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pengajuan berhasil direvisi dan dikirim ulang',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pengajuan berhasil direvisi dan dikirim ulang.'),
                        new OA\Property(property: 'pengajuan', ref: '#/components/schemas/PengajuanResponse'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(
                response: 422,
                description: 'Pengajuan tidak dapat direvisi (status bukan ditolak)',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function update(Request $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        if (! in_array($pengajuan->status, ['ditolak_desa', 'ditolak_kecamatan'])) {
            return response()->json([
                'message' => 'Pengajuan ini tidak dapat direvisi.',
            ], 422);
        }

        $request->validate([
            'no_whatsapp' => ['required', 'string', 'max:20'],
        ]);

        $pengajuan->update([
            'no_whatsapp' => $request->no_whatsapp,
            'status'      => 'berkas_diterima',
        ]);

        return response()->json([
            'message'   => 'Pengajuan berhasil direvisi dan dikirim ulang.',
            'pengajuan' => new PengajuanResource($pengajuan->fresh()->load('user')),
        ]);
    }

    public function submitForm(Request $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        if ($pengajuan->status !== 'berkas_diterima') {
            return response()->json([
                'message' => 'Form tidak dapat diubah setelah pengajuan diproses.',
            ], 422);
        }

        $data = match ($pengajuan->jenis_layanan) {
            'kia'            => $this->validateAndGetFormKia($request),
            '3_in_1'         => $this->validateAndGetForm3In1($request),
            'kk_penambahan'  => $this->validateAndGetFormKkPenambahan($request),
            'kk_pengurangan' => $this->validateAndGetFormKkPengurangan($request),
            'kk_perbaikan'   => $this->validateAndGetFormKkPerbaikan($request),
            default          => null,
        };

        if (is_null($data)) {
            return response()->json(['message' => 'Jenis layanan tidak memiliki form detail.'], 422);
        }

        $formModel = match ($pengajuan->jenis_layanan) {
            'kia'            => FormKia::class,
            '3_in_1'         => Form3In1::class,
            'kk_penambahan'  => FormKkPenambahan::class,
            'kk_pengurangan' => FormKkPengurangan::class,
            'kk_perbaikan'   => FormKkPerbaikan::class,
        };

        $formModel::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            $data
        );

        return response()->json([
            'message' => 'Data form berhasil disimpan.',
        ]);
    }

    #[OA\Post(
        path: '/pengajuan/{pengajuan}/dokumen',
        summary: 'Upload dokumen pendukung',
        description: "Upload satu file dokumen per request. Nama field (`field`) berbeda sesuai jenis_layanan:\n- **kia**: file_akta_kelahiran, file_kk, file_surat_nikah, file_foto_anak\n- **3_in_1**: file_sk_lahir, file_kk, file_ktp_ortu, file_surat_nikah, file_foto_anak\n- **kk_penambahan**: file_kk_asli, file_sk_lahir_akta, file_ktp_suami_istri, file_surat_nikah\n- **kk_pengurangan**: file_kk_asli, file_ktp_asli, file_sk_pindah_mati\n- **kk_perbaikan**: file_pendukung (bisa diupload berkali-kali)",
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'pengajuan', in: 'path', required: true, description: 'ID pengajuan', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['field', 'dokumen'],
                    properties: [
                        new OA\Property(property: 'field', type: 'string', example: 'file_kk', description: 'Nama field dokumen sesuai jenis layanan'),
                        new OA\Property(property: 'dokumen', type: 'string', format: 'binary', description: 'File JPG/PNG/PDF, maks 5MB'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dokumen berhasil diupload',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Dokumen berhasil diupload.'),
                        new OA\Property(property: 'field', type: 'string', example: 'file_kk'),
                        new OA\Property(property: 'path', type: 'string', example: 'pengajuan/1/file_kk/document.jpg'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(
                response: 422,
                description: 'Validasi gagal atau field tidak valid',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function uploadDokumen(Request $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        if ($pengajuan->status !== 'berkas_diterima') {
            return response()->json([
                'message' => 'Dokumen tidak dapat diubah setelah pengajuan diproses.',
            ], 422);
        }

        $request->validate([
            'field'   => ['required', 'string'],
            'dokumen' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $formDetail = $pengajuan->getFormDetail();

        if (! $formDetail) {
            return response()->json([
                'message' => 'Isi detail form terlebih dahulu sebelum upload dokumen.',
            ], 422);
        }

        $allowedFields = array_keys($formDetail->getFileDokumen());
        if (! in_array($request->field, $allowedFields)) {
            return response()->json([
                'message' => 'Field dokumen tidak valid untuk jenis layanan ini.',
            ], 422);
        }

        $path = $request->file('dokumen')->store(
            "pengajuan/{$pengajuan->id}/{$request->field}",
            'local'
        );

        if ($pengajuan->jenis_layanan === 'kk_perbaikan' && $request->field === 'file_pendukung') {
            $existing = $formDetail->file_pendukung ?? [];
            $existing[] = $path;
            $formDetail->update(['file_pendukung' => $existing]);
        } else {
            $formDetail->update([$request->field => $path]);
        }

        return response()->json([
            'message' => 'Dokumen berhasil diupload.',
            'field'   => $request->field,
            'path'    => $path,
        ]);
    }

    #[OA\Get(
        path: '/pengajuan/{pengajuan}/status',
        summary: 'Cek status terkini pengajuan',
        description: 'Mengembalikan status terkini beserta riwayat perubahan status pengajuan. Gunakan endpoint ini untuk polling status dari mobile app.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'pengajuan', in: 'path', required: true, description: 'ID pengajuan', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Status pengajuan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diproses_kecamatan', 'selesai'],
                            example: 'diverifikasi_desa'
                        ),
                        new OA\Property(property: 'label_status', type: 'string', example: 'Diverifikasi Desa'),
                        new OA\Property(property: 'lokasi_dokumen', type: 'string', nullable: true, example: null, description: 'Path softfile jika sudah selesai'),
                        new OA\Property(
                            property: 'riwayat_statuses',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/RiwayatStatusResponse')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Pengajuan tidak ditemukan'),
        ]
    )]
    public function status(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        $pengajuan->load('riwayatStatuses');

        return response()->json([
            'id'               => $pengajuan->id,
            'status'           => $pengajuan->status,
            'label_status'     => $pengajuan->getLabelStatus(),
            'lokasi_dokumen'   => $pengajuan->lokasi_dokumen,
            'riwayat_statuses' => $pengajuan->riwayatStatuses->map(fn($r) => [
                'status'  => $r->status_riwayat,
                'catatan' => $r->catatan,
                'waktu'   => $r->created_at->toIso8601String(),
            ]),
        ]);
    }

    // --- Private Helpers ---

    private function authorizeWarga(Pengajuan $pengajuan): void
    {
        abort_unless(
            $pengajuan->user_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke pengajuan ini.'
        );
    }

    private function validateAndGetFormKia(Request $request): array
    {
        return $request->validate([
            'nama_lengkap'         => ['required', 'string', 'max:255'],
            'tempat_lahir'         => ['required', 'string', 'max:255'],
            'tanggal_lahir'        => ['required', 'date'],
            'jenis_kelamin'        => ['required', 'in:L,P'],
            'nama_kepala_keluarga' => ['required', 'string', 'max:255'],
            'agama'                => ['required', 'string', 'max:50'],
            'kewarganegaraan'      => ['required', 'string', 'max:50'],
        ]);
    }

    private function validateAndGetForm3In1(Request $request): array
    {
        return $request->validate([
            'nama_lengkap_pemohon' => ['required', 'string', 'max:255'],
            'desa'                 => ['required', 'string', 'max:255'],
            'alamat_lengkap'       => ['required', 'string', 'max:500'],
            'nama_anak'            => ['required', 'string', 'max:255'],
            'tanggal_lahir_anak'   => ['required', 'date'],
        ]);
    }

    private function validateAndGetFormKkPenambahan(Request $request): array
    {
        return $request->validate([
            'nama_kepala_keluarga'   => ['required', 'string', 'max:255'],
            'nomor_kk'               => ['required', 'string', 'max:20'],
            'alamat'                 => ['required', 'string', 'max:500'],
            'nama_dusun'             => ['nullable', 'string', 'max:100'],
            'rt'                     => ['required', 'string', 'max:10'],
            'rw'                     => ['required', 'string', 'max:10'],
            'nama_ketua_rt'          => ['nullable', 'string', 'max:255'],
            'nama_ketua_rw'          => ['nullable', 'string', 'max:255'],
            'nama_lengkap_tambahan'  => ['required', 'string', 'max:255'],
            'jenis_kelamin_tambahan' => ['required', 'in:L,P'],
            'tempat_lahir_tambahan'  => ['required', 'string', 'max:255'],
            'tanggal_lahir_tambahan' => ['required', 'date'],
            'status_hubungan'        => ['required', 'string', 'max:100'],
            'kelainan_fisik_mental'  => ['nullable', 'string', 'max:255'],
            'penyandang_cacat'       => ['nullable', 'string', 'max:255'],
            'agama'                  => ['required', 'string', 'max:50'],
            'nama_ibu_kandung'       => ['required', 'string', 'max:255'],
            'nik_ibu'                => ['required', 'string', 'digits:16'],
            'nama_ayah_kandung'      => ['required', 'string', 'max:255'],
            'nik_ayah'               => ['required', 'string', 'digits:16'],
        ]);
    }

    private function validateAndGetFormKkPengurangan(Request $request): array
    {
        return $request->validate([
            'alasan_pengurangan'     => ['required', 'string', 'max:500'],
            'nama_lengkap_anggota'   => ['required', 'string', 'max:255'],
            'alamat_lengkap_anggota' => ['required', 'string', 'max:500'],
            'nik_anggota'            => ['required', 'string', 'digits:16'],
        ]);
    }

    private function validateAndGetFormKkPerbaikan(Request $request): array
    {
        return $request->validate([
            'jenis_perbaikan_id'           => ['required', 'integer', 'exists:master_jenis_perbaikan_kks,id'],
            'nama_kepala_keluarga'         => ['required', 'string', 'max:255'],
            'nomor_kk'                     => ['required', 'string', 'max:20'],
            'nama_anggota_yang_diperbaiki' => ['required', 'string', 'max:255'],
            'data_perbaikan'               => ['required', 'array'],
        ]);
    }
}
