<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreTiga1Request;
use App\Http\Resources\PengajuanResource;
use App\Models\Form3In1;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class Tiga1PengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/3-in-1',
        summary: 'Buat pengajuan 3 in 1 baru',
        description: 'Membuat pengajuan layanan 3 in 1 (Akta Kelahiran + KK + KIA) sekaligus mengisi form dan mengupload semua dokumen.',
        tags: ['Pengajuan - 3 in 1'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['nama_anak', 'tanggal_lahir_anak', 'file_sk_lahir', 'file_kk', 'file_ktp_ortu', 'file_surat_nikah', 'file_foto_anak'],
                    properties: [
                        new OA\Property(property: 'nama_anak', type: 'string', example: 'Rian Prasetyo'),
                        new OA\Property(property: 'tanggal_lahir_anak', type: 'string', format: 'date', example: '2024-03-10'),
                        new OA\Property(property: 'file_sk_lahir', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_kk', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_ortu', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pengajuan 3 in 1 berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pengajuan 3 in 1 berhasil dibuat.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PengajuanResponse'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')),
        ]
    )]
    public function store(StoreTiga1Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create($this->buildPengajuanData($user, '3_in_1'));

            Form3In1::create([
                'pengajuan_id'       => $pengajuan->id,
                'nama_anak'          => $request->nama_anak,
                'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
                'file_sk_lahir'         => $this->storeFile($request->file('file_sk_lahir'), 'pengajuan/3-in-1'),
                'file_kk'               => $this->storeFile($request->file('file_kk'), 'pengajuan/3-in-1'),
                'file_ktp_ortu'         => $this->storeFile($request->file('file_ktp_ortu'), 'pengajuan/3-in-1'),
                'file_surat_nikah'      => $this->storeFile($request->file('file_surat_nikah'), 'pengajuan/3-in-1'),
                'file_foto_anak'        => $this->storeFile($request->file('file_foto_anak'), 'pengajuan/3-in-1'),
            ]);

            return response()->json([
                'message' => 'Pengajuan 3 in 1 berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'form3In1'])),
            ], 201);
        });
    }

    #[OA\Post(
        path: '/pengajuan/3-in-1/{id}',
        summary: 'Revisi pengajuan 3 in 1',
        description: 'Mengupdate data form dan/atau dokumen pengajuan 3 in 1. File yang tidak dikirim tidak akan diubah.',
        tags: ['Pengajuan - 3 in 1'],
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
                        new OA\Property(property: 'nama_lengkap', type: 'string'),
                        new OA\Property(property: 'nik', type: 'string'),
                        new OA\Property(property: 'no_whatsapp', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'pekerjaan', type: 'string', nullable: true),
                        new OA\Property(property: 'alamat', type: 'string'),
                        new OA\Property(property: 'desa', type: 'string'),
                        new OA\Property(property: 'rt', type: 'string'),
                        new OA\Property(property: 'rw', type: 'string'),
                        new OA\Property(property: 'nama_anak', type: 'string'),
                        new OA\Property(property: 'tanggal_lahir_anak', type: 'string', format: 'date'),
                        new OA\Property(property: 'file_sk_lahir', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_kk', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_ortu', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan 3 in 1 berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreTiga1Request $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $formData = $request->only(['nama_anak', 'tanggal_lahir_anak']);

            foreach (['file_sk_lahir', 'file_kk', 'file_ktp_ortu', 'file_surat_nikah', 'file_foto_anak'] as $field) {
                if ($request->hasFile($field)) {
                    $this->deleteFile($pengajuan->form3In1?->$field);
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/3-in-1');
                }
            }

            $pengajuan->form3In1()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan 3 in 1 berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'form3In1'])),
            ]);
        });
    }
}
