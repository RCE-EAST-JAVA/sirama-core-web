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
                        'no_whatsapp', 'jenis_perbaikan_id', 'nama_kepala_keluarga',
                        'nomor_kk', 'nama_anggota_yang_diperbaiki', 'data_perbaikan', 'file_pendukung',
                    ],
                    properties: [
                        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
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
            $pengajuan = Pengajuan::create([
                'user_id'       => Auth::id(),
                'jenis_layanan' => 'kk_perbaikan',
                'no_whatsapp'   => $request->no_whatsapp,
                'status'        => 'berkas_diterima',
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
            $pengajuan->update(['no_whatsapp' => $request->no_whatsapp]);

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
