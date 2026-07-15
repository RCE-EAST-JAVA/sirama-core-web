<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanResource;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Pengajuan', description: 'Endpoint pengajuan layanan kependudukan')]
abstract class BasePengajuanController extends Controller
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
                    enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diverifikasi_kecamatan', 'selesai']
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

    #[OA\Get(
        path: '/pengajuan/stats',
        summary: 'Statistik pengajuan milik warga',
        description: 'Mengembalikan jumlah pengajuan milik warga yang sedang login, dikelompokkan per status beserta labelnya.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistik pengajuan per status',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'total', type: 'integer', example: 7),
                                new OA\Property(
                                    property: 'statuses',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'status', type: 'string', example: 'berkas_diterima'),
                                            new OA\Property(property: 'label', type: 'string', example: 'Berkas Diterima'),
                                            new OA\Property(property: 'jumlah', type: 'integer', example: 2),
                                        ]
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function stats(): JsonResponse
    {
        $statusLabels = [
            'berkas_diterima'        => 'Berkas Diterima',
            'diajukan_kembali'       => 'Diajukan Kembali',
            'diverifikasi_desa'      => 'Diverifikasi Desa',
            'ditolak_desa'           => 'Ditolak Desa',
            'diverifikasi_kecamatan' => 'Diverifikasi Kecamatan',
            'ditolak_kecamatan'      => 'Ditolak Kecamatan',
            'selesai'                => 'Selesai',
        ];

        $counts = Pengajuan::selectRaw('status, count(*) as jumlah')
            ->where('user_id', Auth::id())
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statuses = array_map(fn($status, $label) => [
            'status' => $status,
            'label'  => $label,
            'jumlah' => (int) ($counts[$status] ?? 0),
        ], array_keys($statusLabels), $statusLabels);

        return response()->json([
            'data' => [
                'total'    => array_sum(array_column($statuses, 'jumlah')),
                'statuses' => $statuses,
            ],
        ]);
    }

    #[OA\Get(
        path: '/pengajuan/statuses',
        summary: 'Daftar semua status pengajuan',
        description: 'Endpoint publik — tidak perlu token. Mengembalikan semua nilai status pengajuan beserta labelnya. Berguna untuk mengisi dropdown filter.',
        tags: ['Pengajuan'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar status pengajuan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'value', type: 'string', example: 'berkas_diterima'),
                                    new OA\Property(property: 'label', type: 'string', example: 'Berkas Diterima'),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function statuses(): JsonResponse
    {
        $statusLabels = [
            'berkas_diterima'        => 'Berkas Diterima',
            'diajukan_kembali'       => 'Diajukan Kembali',
            'diverifikasi_desa'      => 'Diverifikasi Desa',
            'ditolak_desa'           => 'Ditolak Desa',
            'diverifikasi_kecamatan' => 'Diverifikasi Kecamatan',
            'ditolak_kecamatan'      => 'Ditolak Kecamatan',
            'selesai'                => 'Selesai',
        ];

        $statuses = array_map(
            fn($value, $label) => ['value' => $value, 'label' => $label],
            array_keys($statusLabels),
            $statusLabels
        );

        return response()->json(['data' => $statuses]);
    }

    #[OA\Get(
        path: '/pengajuan/{id}',
        summary: 'Detail satu pengajuan',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detail pengajuan', content: new OA\JsonContent(ref: '#/components/schemas/PengajuanResponse')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function show(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        $pengajuan->load([
            'user',
            'formKia',
            'form3In1',
            'formKkPenambahan',
            'formKkPengurangan',
            'formKkPerbaikan',
            'riwayatStatuses',
        ]);

        return response()->json([
            'data' => new PengajuanResource($pengajuan),
        ]);
    }

    #[OA\Get(
        path: '/pengajuan/{id}/status',
        summary: 'Cek status pengajuan (polling)',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Status terkini pengajuan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'diverifikasi_desa'),
                        new OA\Property(property: 'label_status', type: 'string', example: 'Diverifikasi Desa'),
                        new OA\Property(property: 'lokasi_dokumen', type: 'string', nullable: true),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function status(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return response()->json([
            'status'         => $pengajuan->status,
            'label_status'   => $pengajuan->getLabelStatus(),
            'lokasi_dokumen' => $pengajuan->lokasi_dokumen,
        ]);
    }

    /**
     * Bangun data identitas pemohon dari profil user sebagai snapshot saat pengajuan dibuat.
     */
    protected function buildPengajuanData(\App\Models\User $user, string $jenisLayanan): array
    {
        return [
            'user_id'       => $user->id,
            'jenis_layanan' => $jenisLayanan,
            'status'        => 'berkas_diterima',
            'nama_lengkap'  => $user->name,
            'nik'           => $user->nik,
            'no_whatsapp'   => $user->no_whatsapp,
            'tanggal_lahir' => $user->tanggal_lahir,
            'jenis_kelamin' => $user->jenis_kelamin,
            'pekerjaan'     => $user->pekerjaan,
            'alamat'        => $user->alamat,
            'desa'          => $user->desa,
            'rt'            => $user->rt,
            'rw'            => $user->rw,
        ];
    }

    /**
     * Serve file private dari disk local dengan autentikasi Sanctum.
     * GET /api/pengajuan/download?path=pengajuan/kia/xxx.pdf
     */
    public function download(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\JsonResponse
    {
        $path = $request->query('path');

        if (!$path) {
            return response()->json(['message' => 'Parameter path diperlukan.'], 422);
        }

        // Cegah path traversal
        $path = ltrim($path, '/');
        if (str_contains($path, '..')) {
            return response()->json(['message' => 'Path tidak valid.'], 422);
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('local');

        if (!$disk->exists($path)) {
            return response()->json(['message' => 'File tidak ditemukan.'], 404);
        }

        $fileName = basename($path);
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return $disk->download($path, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Pastikan pengajuan milik user yang sedang login.
     */
    protected function authorizeWarga(Pengajuan $pengajuan): void
    {
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Pastikan pengajuan boleh diupdate oleh warga.
     * Hanya boleh update jika status ditolak_desa, ditolak_kecamatan, atau diajukan_kembali.
     */
    protected function authorizeCanUpdate(Pengajuan $pengajuan): void
    {
        $allowedStatuses = ['ditolak_desa', 'ditolak_kecamatan', 'diajukan_kembali'];
        if (!in_array($pengajuan->status, $allowedStatuses)) {
            abort(403, 'Pengajuan tidak dapat diubah pada status saat ini.');
        }
    }

    /**
     * Setelah warga merevisi pengajuan yang ditolak, ubah status ke diajukan_kembali
     * dan catat di riwayat status.
     */
    protected function handleResubmit(Pengajuan $pengajuan): void
    {
        if (!in_array($pengajuan->status, ['ditolak_desa', 'ditolak_kecamatan'])) {
            return;
        }

        $pengajuan->update(['status' => 'diajukan_kembali']);

        \App\Models\RiwayatStatus::create([
            'pengajuan_id'   => $pengajuan->id,
            'status_riwayat' => 'diajukan_kembali',
            'catatan'        => 'Warga mengajukan kembali setelah revisi.',
        ]);

        event(new \App\Events\StatusPengajuanUpdated($pengajuan));
    }

    /**
     * Simpan file ke storage lokal (private) dan kembalikan path-nya.
     */
    protected function storeFile(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'local');
    }

    /**
     * Hapus file lama dari storage lokal (private) jika ada.
     */
    protected function deleteFile(?string $path): void
    {
        if ($path && \Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
        }
    }
}
