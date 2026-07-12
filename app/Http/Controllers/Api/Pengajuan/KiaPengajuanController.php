<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreKiaRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormKia;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class KiaPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/kia',
        summary: 'Buat pengajuan KIA baru',
        description: 'Membuat pengajuan Kartu Identitas Anak sekaligus mengisi form dan mengupload semua dokumen dalam satu request (multipart/form-data).',
        tags: ['Pengajuan - KIA'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['no_whatsapp', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'nama_kepala_keluarga', 'agama', 'kewarganegaraan', 'file_akta_kelahiran', 'file_kk', 'file_surat_nikah', 'file_foto_anak'],
                    properties: [
                        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
                        new OA\Property(property: 'nama_lengkap', type: 'string', example: 'Budi Santoso'),
                        new OA\Property(property: 'tempat_lahir', type: 'string', example: 'Bandung'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '2020-01-15'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Santoso'),
                        new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
                        new OA\Property(property: 'kewarganegaraan', type: 'string', example: 'WNI'),
                        new OA\Property(property: 'file_akta_kelahiran', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_kk', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pengajuan KIA berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pengajuan KIA berhasil dibuat.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PengajuanResponse'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')),
        ]
    )]
    public function store(StoreKiaRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $pengajuan = Pengajuan::create([
                'user_id'       => Auth::id(),
                'jenis_layanan' => 'kia',
                'no_whatsapp'   => $request->no_whatsapp,
                'status'        => 'berkas_diterima',
            ]);

            FormKia::create([
                'pengajuan_id'         => $pengajuan->id,
                'nama_lengkap'         => $request->nama_lengkap,
                'tempat_lahir'         => $request->tempat_lahir,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
                'agama'                => $request->agama,
                'kewarganegaraan'      => $request->kewarganegaraan,
                'file_akta_kelahiran'  => $this->storeFile($request->file('file_akta_kelahiran'), 'pengajuan/kia'),
                'file_kk'              => $this->storeFile($request->file('file_kk'), 'pengajuan/kia'),
                'file_surat_nikah'     => $this->storeFile($request->file('file_surat_nikah'), 'pengajuan/kia'),
                'file_foto_anak'       => $this->storeFile($request->file('file_foto_anak'), 'pengajuan/kia'),
            ]);

            return response()->json([
                'message' => 'Pengajuan KIA berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKia'])),
            ], 201);
        });
    }

    #[OA\Put(
        path: '/pengajuan/kia/{id}',
        summary: 'Revisi pengajuan KIA',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KIA. File yang tidak dikirim tidak akan diubah.',
        tags: ['Pengajuan - KIA'],
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
                        new OA\Property(property: 'no_whatsapp', type: 'string'),
                        new OA\Property(property: 'nama_lengkap', type: 'string'),
                        new OA\Property(property: 'tempat_lahir', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'agama', type: 'string'),
                        new OA\Property(property: 'kewarganegaraan', type: 'string'),
                        new OA\Property(property: 'file_akta_kelahiran', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_kk', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan KIA berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreKiaRequest $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'no_whatsapp' => $request->no_whatsapp,
            ]);

            $formData = $request->only([
                'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
                'jenis_kelamin', 'nama_kepala_keluarga', 'agama', 'kewarganegaraan',
            ]);

            foreach (['file_akta_kelahiran', 'file_kk', 'file_surat_nikah', 'file_foto_anak'] as $field) {
                if ($request->hasFile($field)) {
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/kia');
                }
            }

            $pengajuan->formKia()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan KIA berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKia'])),
            ]);
        });
    }
}
