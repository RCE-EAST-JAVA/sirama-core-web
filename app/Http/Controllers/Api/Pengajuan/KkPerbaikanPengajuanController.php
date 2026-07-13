<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreKkPerbaikanRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormKkPerbaikan;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class KkPerbaikanPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/kk-perbaikan',
        summary: 'Buat pengajuan perbaikan data KK',
        description: 'Membuat pengajuan perbaikan data KK sekaligus mengisi form dan mengupload file pendukung (bisa lebih dari satu file).',
        tags: ['Pengajuan - KK Perbaikan'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: [
                        'nama_lengkap', 'nik', 'no_whatsapp', 'tanggal_lahir', 'jenis_kelamin',
                        'alamat', 'desa', 'rt', 'rw',
                        'jenis_perbaikan_id', 'nama_kepala_keluarga',
                        'nomor_kk', 'nama_anggota_yang_diperbaiki', 'data_perbaikan', 'file_pendukung',
                    ],
                    properties: [
                        // Data diri pemohon
                        new OA\Property(property: 'nama_lengkap', type: 'string', example: 'Budi Santoso'),
                        new OA\Property(property: 'nik', type: 'string', example: '3277010101900001'),
                        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '1990-01-01'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'pekerjaan', type: 'string', nullable: true, example: 'Wiraswasta'),
                        new OA\Property(property: 'alamat', type: 'string', example: 'Jl. Merdeka No. 1'),
                        new OA\Property(property: 'desa', type: 'string', example: 'Desa Sukamaju'),
                        new OA\Property(property: 'rt', type: 'string', example: '001'),
                        new OA\Property(property: 'rw', type: 'string', example: '002'),
                        // Data spesifik KK Perbaikan
                        new OA\Property(property: 'jenis_perbaikan_id', type: 'integer', example: 1),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk', type: 'string'),
                        new OA\Property(property: 'nama_anggota_yang_diperbaiki', type: 'string'),
                        new OA\Property(
                            property: 'data_perbaikan',
                            type: 'object',
                            example: ['nama_lama' => 'Budi', 'nama_baru' => 'Budi Santoso'],
                            additionalProperties: new OA\AdditionalProperties(type: 'string')
                        ),
                        new OA\Property(
                            property: 'file_pendukung[]',
                            type: 'array',
                            items: new OA\Items(type: 'string', format: 'binary'),
                            description: 'Satu atau lebih file pendukung'
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Pengajuan KK Perbaikan berhasil dibuat'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreKkPerbaikanRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create([
                'user_id'       => $user->id,
                'jenis_layanan' => 'kk_perbaikan',
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

            // Simpan setiap file pendukung, hasilnya array of paths
            $filePaths = collect($request->file('file_pendukung'))
                ->map(fn($file) => $this->storeFile($file, 'pengajuan/kk-perbaikan'))
                ->values()
                ->all();

            FormKkPerbaikan::create([
                'pengajuan_id'                 => $pengajuan->id,
                'jenis_perbaikan_id'           => $request->jenis_perbaikan_id,
                'nama_kepala_keluarga'         => $request->nama_kepala_keluarga,
                'nomor_kk'                     => $request->nomor_kk,
                'nama_anggota_yang_diperbaiki' => $request->nama_anggota_yang_diperbaiki,
                'data_perbaikan'               => $request->data_perbaikan,
                'file_pendukung'               => $filePaths,
            ]);

            return response()->json([
                'message' => 'Pengajuan KK Perbaikan berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPerbaikan'])),
            ], 201);
        });
    }

    #[OA\Put(
        path: '/pengajuan/kk-perbaikan/{id}',
        summary: 'Revisi pengajuan KK Perbaikan',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KK Perbaikan. Jika file_pendukung dikirim, semua file lama akan digantikan.',
        tags: ['Pengajuan - KK Perbaikan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan KK Perbaikan berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreKkPerbaikanRequest $request, Pengajuan $pengajuan): JsonResponse
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
                'jenis_perbaikan_id', 'nama_kepala_keluarga', 'nomor_kk',
                'nama_anggota_yang_diperbaiki', 'data_perbaikan',
            ]);

            if ($request->hasFile('file_pendukung')) {
                $formData['file_pendukung'] = collect($request->file('file_pendukung'))
                    ->map(fn($file) => $this->storeFile($file, 'pengajuan/kk-perbaikan'))
                    ->values()
                    ->all();
            }

            $pengajuan->formKkPerbaikan()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan KK Perbaikan berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPerbaikan'])),
            ]);
        });
    }
}
