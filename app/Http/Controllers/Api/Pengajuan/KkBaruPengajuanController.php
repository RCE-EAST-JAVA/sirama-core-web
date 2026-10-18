<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreKkBaruRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormKkBaru;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class KkBaruPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/kk-baru',
        summary: 'Buat pengajuan KK Baru / Pisah KK',
        description: 'Membuat pengajuan pembuatan KK baru atau pisah KK sekaligus mengisi form dan mengupload 7 dokumen dalam satu request.',
        tags: ['Pengajuan - KK Baru'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: [
                        'nama_kepala_keluarga', 'nomor_kk_lama', 'nama_ketua_rt', 'nama_ketua_rw',
                        'nama_lengkap_suami', 'tempat_lahir_suami', 'tanggal_lahir_suami',
                        'agama_suami', 'pekerjaan_suami',
                        'nama_lengkap_istri', 'tempat_lahir_istri', 'tanggal_lahir_istri',
                        'agama_istri', 'pekerjaan_istri',
                        'alamat_baru', 'rt', 'rw', 'desa', 'alasan_pembuatan',
                        'file_kk_lama', 'file_ktp_suami', 'file_ktp_istri',
                        'file_surat_domisili', 'file_surat_nikah',
                        'file_ijazah_suami', 'file_ijazah_istri',
                    ],
                    properties: [
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk_lama', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rt', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rw', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_suami', type: 'string'),
                        new OA\Property(property: 'tempat_lahir_suami', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_suami', type: 'string', format: 'date'),
                        new OA\Property(property: 'agama_suami', type: 'string'),
                        new OA\Property(property: 'pekerjaan_suami', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_istri', type: 'string'),
                        new OA\Property(property: 'tempat_lahir_istri', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_istri', type: 'string', format: 'date'),
                        new OA\Property(property: 'agama_istri', type: 'string'),
                        new OA\Property(property: 'pekerjaan_istri', type: 'string'),
                        new OA\Property(property: 'alamat_baru', type: 'string'),
                        new OA\Property(property: 'rt', type: 'string'),
                        new OA\Property(property: 'rw', type: 'string'),
                        new OA\Property(property: 'desa', type: 'string'),
                        new OA\Property(property: 'alasan_pembuatan', type: 'string'),
                        new OA\Property(property: 'file_kk_lama', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_suami', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_istri', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_domisili', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ijazah_suami', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ijazah_istri', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Pengajuan KK Baru berhasil dibuat'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreKkBaruRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create($this->buildPengajuanData($user, 'kk_baru'));

            FormKkBaru::create([
                'pengajuan_id'           => $pengajuan->id,
                'nama_kepala_keluarga'   => $request->nama_kepala_keluarga,
                'nomor_kk_lama'          => $request->nomor_kk_lama,
                'nama_ketua_rt'          => $request->nama_ketua_rt,
                'nama_ketua_rw'          => $request->nama_ketua_rw,
                'nama_lengkap_suami'     => $request->nama_lengkap_suami,
                'tempat_lahir_suami'     => $request->tempat_lahir_suami,
                'tanggal_lahir_suami'    => $request->tanggal_lahir_suami,
                'agama_suami'            => $request->agama_suami,
                'pekerjaan_suami'        => $request->pekerjaan_suami,
                'nama_lengkap_istri'     => $request->nama_lengkap_istri,
                'tempat_lahir_istri'     => $request->tempat_lahir_istri,
                'tanggal_lahir_istri'    => $request->tanggal_lahir_istri,
                'agama_istri'            => $request->agama_istri,
                'pekerjaan_istri'        => $request->pekerjaan_istri,
                'alamat_baru'            => $request->alamat_baru,
                'rt'                     => $request->rt,
                'rw'                     => $request->rw,
                'desa'                   => $request->desa,
                'alasan_pembuatan'       => $request->alasan_pembuatan,
                'file_kk_lama'           => $this->storeFile($request->file('file_kk_lama'), 'pengajuan/kk-baru'),
                'file_ktp_suami'         => $this->storeFile($request->file('file_ktp_suami'), 'pengajuan/kk-baru'),
                'file_ktp_istri'         => $this->storeFile($request->file('file_ktp_istri'), 'pengajuan/kk-baru'),
                'file_surat_domisili'    => $this->storeFile($request->file('file_surat_domisili'), 'pengajuan/kk-baru'),
                'file_surat_nikah'       => $this->storeFile($request->file('file_surat_nikah'), 'pengajuan/kk-baru'),
                'file_ijazah_suami'      => $this->storeFile($request->file('file_ijazah_suami'), 'pengajuan/kk-baru'),
                'file_ijazah_istri'      => $this->storeFile($request->file('file_ijazah_istri'), 'pengajuan/kk-baru'),
            ]);

            $this->sendPengajuanCreatedNotifications($pengajuan);

            return response()->json([
                'message' => 'Pengajuan KK Baru berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkBaru'])),
            ], 201);
        });
    }

    #[OA\Post(
        path: '/pengajuan/kk-baru/{id}',
        summary: 'Revisi pengajuan KK Baru',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KK Baru. File yang tidak dikirim tidak akan diubah.',
        tags: ['Pengajuan - KK Baru'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk_lama', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rt', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rw', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_suami', type: 'string'),
                        new OA\Property(property: 'tempat_lahir_suami', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_suami', type: 'string', format: 'date'),
                        new OA\Property(property: 'agama_suami', type: 'string'),
                        new OA\Property(property: 'pekerjaan_suami', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_istri', type: 'string'),
                        new OA\Property(property: 'tempat_lahir_istri', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_istri', type: 'string', format: 'date'),
                        new OA\Property(property: 'agama_istri', type: 'string'),
                        new OA\Property(property: 'pekerjaan_istri', type: 'string'),
                        new OA\Property(property: 'alamat_baru', type: 'string'),
                        new OA\Property(property: 'rt', type: 'string'),
                        new OA\Property(property: 'rw', type: 'string'),
                        new OA\Property(property: 'desa', type: 'string'),
                        new OA\Property(property: 'alasan_pembuatan', type: 'string'),
                        new OA\Property(property: 'file_kk_lama', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_suami', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_istri', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_domisili', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ijazah_suami', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ijazah_istri', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan KK Baru berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreKkBaruRequest $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);
        $this->authorizeCanUpdate($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $formData = $request->only([
                'nama_kepala_keluarga', 'nomor_kk_lama', 'nama_ketua_rt', 'nama_ketua_rw',
                'nama_lengkap_suami', 'tempat_lahir_suami', 'tanggal_lahir_suami',
                'agama_suami', 'pekerjaan_suami',
                'nama_lengkap_istri', 'tempat_lahir_istri', 'tanggal_lahir_istri',
                'agama_istri', 'pekerjaan_istri',
                'alamat_baru', 'rt', 'rw', 'desa', 'alasan_pembuatan',
            ]);

            foreach ([
                'file_kk_lama', 'file_ktp_suami', 'file_ktp_istri',
                'file_surat_domisili', 'file_surat_nikah',
                'file_ijazah_suami', 'file_ijazah_istri'
            ] as $field) {
                if ($request->hasFile($field)) {
                    $this->deleteFile($pengajuan->formKkBaru?->$field);
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/kk-baru');
                }
            }

            $pengajuan->formKkBaru()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            $this->handleResubmit($pengajuan);

            return response()->json([
                'message' => 'Pengajuan KK Baru berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->fresh()->load(['user', 'formKkBaru'])),
            ]);
        });
    }
}
