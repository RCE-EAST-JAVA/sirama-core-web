<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreKkPenambahanRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormKkPenambahan;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class KkPenambahanPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/kk-penambahan',
        summary: 'Buat pengajuan penambahan anggota KK',
        description: 'Membuat pengajuan penambahan anggota KK sekaligus mengisi form dan mengupload semua dokumen dalam satu request.',
        tags: ['Pengajuan - KK Penambahan'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: [
                        'nama_lengkap', 'nik', 'no_whatsapp', 'tanggal_lahir', 'jenis_kelamin',
                        'alamat', 'desa', 'rt', 'rw',
                        'nama_kepala_keluarga', 'nomor_kk', 'nama_ketua_rt', 'nama_ketua_rw',
                        'nama_lengkap_tambahan', 'jenis_kelamin_tambahan', 'tempat_lahir_tambahan',
                        'tanggal_lahir_tambahan', 'status_hubungan', 'kelainan_fisik_mental',
                        'penyandang_cacat', 'agama', 'nama_ibu_kandung', 'nik_ibu',
                        'nama_ayah_kandung', 'nik_ayah',
                        'file_kk_asli', 'file_sk_lahir_akta', 'file_ktp_suami_istri', 'file_surat_nikah',
                    ],
                    properties: [
                        new OA\Property(property: 'nama_lengkap', type: 'string', example: 'Budi Santoso'),
                        new OA\Property(property: 'nik', type: 'string', example: '3277010101900001'),
                        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '1990-01-01'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'pekerjaan', type: 'string', nullable: true),
                        new OA\Property(property: 'alamat', type: 'string'),
                        new OA\Property(property: 'desa', type: 'string'),
                        new OA\Property(property: 'rt', type: 'string'),
                        new OA\Property(property: 'rw', type: 'string'),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rt', type: 'string'),
                        new OA\Property(property: 'nama_ketua_rw', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_tambahan', type: 'string'),
                        new OA\Property(property: 'jenis_kelamin_tambahan', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'tempat_lahir_tambahan', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_tambahan', type: 'string', format: 'date'),
                        new OA\Property(property: 'status_hubungan', type: 'string'),
                        new OA\Property(property: 'kelainan_fisik_mental', type: 'string'),
                        new OA\Property(property: 'penyandang_cacat', type: 'string'),
                        new OA\Property(property: 'agama', type: 'string'),
                        new OA\Property(property: 'nama_ibu_kandung', type: 'string'),
                        new OA\Property(property: 'nik_ibu', type: 'string'),
                        new OA\Property(property: 'nama_ayah_kandung', type: 'string'),
                        new OA\Property(property: 'nik_ayah', type: 'string'),
                        new OA\Property(property: 'file_kk_asli', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_sk_lahir_akta', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_suami_istri', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Pengajuan KK Penambahan berhasil dibuat'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreKkPenambahanRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create([
                'user_id'       => $user->id,
                'jenis_layanan' => 'kk_penambahan',
                'status'        => 'berkas_diterima',
                'no_whatsapp'   => $request->no_whatsapp   ?? $user->no_whatsapp,
                'nama_lengkap'  => $request->nama_lengkap  ?? $user->name,
                'nik'           => $request->nik            ?? $user->nik,
                'tanggal_lahir' => $request->tanggal_lahir ?? $user->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin ?? $user->jenis_kelamin,
                'pekerjaan'     => $request->pekerjaan     ?? $user->pekerjaan,
                'alamat'        => $request->alamat         ?? $user->alamat,
                'desa'          => $request->desa           ?? $user->desa,
                'rt'            => $request->rt             ?? $user->rt,
                'rw'            => $request->rw             ?? $user->rw,
            ]);

            FormKkPenambahan::create([
                'pengajuan_id'           => $pengajuan->id,
                'nama_kepala_keluarga'   => $request->nama_kepala_keluarga,
                'nomor_kk'               => $request->nomor_kk,
                'nama_ketua_rt'          => $request->nama_ketua_rt,
                'nama_ketua_rw'          => $request->nama_ketua_rw,
                'nama_lengkap_tambahan'  => $request->nama_lengkap_tambahan,
                'jenis_kelamin_tambahan' => $request->jenis_kelamin_tambahan,
                'tempat_lahir_tambahan'  => $request->tempat_lahir_tambahan,
                'tanggal_lahir_tambahan' => $request->tanggal_lahir_tambahan,
                'status_hubungan'        => $request->status_hubungan,
                'kelainan_fisik_mental'  => $request->kelainan_fisik_mental,
                'penyandang_cacat'       => $request->penyandang_cacat,
                'agama'                  => $request->agama,
                'nama_ibu_kandung'       => $request->nama_ibu_kandung,
                'nik_ibu'                => $request->nik_ibu,
                'nama_ayah_kandung'      => $request->nama_ayah_kandung,
                'nik_ayah'               => $request->nik_ayah,
                'file_kk_asli'           => $this->storeFile($request->file('file_kk_asli'), 'pengajuan/kk-penambahan'),
                'file_sk_lahir_akta'     => $this->storeFile($request->file('file_sk_lahir_akta'), 'pengajuan/kk-penambahan'),
                'file_ktp_suami_istri'   => $this->storeFile($request->file('file_ktp_suami_istri'), 'pengajuan/kk-penambahan'),
                'file_surat_nikah'       => $this->storeFile($request->file('file_surat_nikah'), 'pengajuan/kk-penambahan'),
            ]);

            return response()->json([
                'message' => 'Pengajuan KK Penambahan berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPenambahan'])),
            ], 201);
        });
    }

    #[OA\Put(
        path: '/pengajuan/kk-penambahan/{id}',
        summary: 'Revisi pengajuan KK Penambahan',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KK Penambahan. File yang tidak dikirim tidak akan diubah.',
        tags: ['Pengajuan - KK Penambahan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan KK Penambahan berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreKkPenambahanRequest $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'no_whatsapp'   => $request->no_whatsapp,
                'nama_lengkap'  => $request->nama_lengkap,
                'nik'           => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan'     => $request->pekerjaan,
                'alamat'        => $request->alamat,
                'desa'          => $request->desa,
                'rt'            => $request->rt,
                'rw'            => $request->rw,
            ]);

            $formData = $request->only([
                'nama_kepala_keluarga', 'nomor_kk',
                'nama_ketua_rt', 'nama_ketua_rw', 'nama_lengkap_tambahan', 'jenis_kelamin_tambahan',
                'tempat_lahir_tambahan', 'tanggal_lahir_tambahan', 'status_hubungan',
                'kelainan_fisik_mental', 'penyandang_cacat', 'agama', 'nama_ibu_kandung',
                'nik_ibu', 'nama_ayah_kandung', 'nik_ayah',
            ]);

            foreach (['file_kk_asli', 'file_sk_lahir_akta', 'file_ktp_suami_istri', 'file_surat_nikah'] as $field) {
                if ($request->hasFile($field)) {
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/kk-penambahan');
                }
            }

            $pengajuan->formKkPenambahan()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan KK Penambahan berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPenambahan'])),
            ]);
        });
    }
}
