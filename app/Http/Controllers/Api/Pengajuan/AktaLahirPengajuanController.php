<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreAktaLahirRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormAktaLahir;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class AktaLahirPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/akta-lahir',
        summary: 'Buat pengajuan Akta Kelahiran baru',
        description: 'Membuat pengajuan Akta Kelahiran sekaligus mengisi form dan mengupload semua dokumen dalam satu request (multipart/form-data).',
        tags: ['Pengajuan - Akta Lahir'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: [
                        'nama_anak', 'tanggal_lahir_anak',
                        'file_sk_lahir', 'file_kk', 'file_ktp_ayah', 'file_ktp_ibu', 'file_surat_nikah',
                    ],
                    properties: [
                        // Data spesifik Akta Lahir
                        new OA\Property(property: 'nama_anak', type: 'string', example: 'Rian Santoso'),
                        new OA\Property(property: 'tanggal_lahir_anak', type: 'string', format: 'date', example: '2024-03-10'),
                        new OA\Property(property: 'file_sk_lahir', type: 'string', format: 'binary', description: 'Surat keterangan lahir dari bidan/klinik/RS/pemerintah desa'),
                        new OA\Property(property: 'file_kk', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_ayah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_ibu', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Pengajuan Akta Lahir berhasil dibuat'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreAktaLahirRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create($this->buildPengajuanData($user, 'akta_kelahiran'));

            $formData = $request->only(['nama_anak', 'tanggal_lahir_anak']);

            foreach (['file_sk_lahir', 'file_kk', 'file_ktp_ayah', 'file_ktp_ibu', 'file_surat_nikah'] as $field) {
                if ($request->hasFile($field)) {
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/akta-lahir');
                }
            }

            $pengajuan->formAktaLahir()->create($formData);

            return response()->json([
                'message' => 'Pengajuan Akta Kelahiran berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formAktaLahir'])),
            ], 201);
        });
    }

    #[OA\Post(
        path: '/pengajuan/akta-lahir/{id}',
        summary: 'Update pengajuan Akta Kelahiran',
        tags: ['Pengajuan - Akta Lahir'],
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
                        new OA\Property(property: 'file_ktp_ayah', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_ibu', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan Akta Lahir berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreAktaLahirRequest $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);
        $this->authorizeCanUpdate($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $formData = $request->only(['nama_anak', 'tanggal_lahir_anak']);

            foreach (['file_sk_lahir', 'file_kk', 'file_ktp_ayah', 'file_ktp_ibu', 'file_surat_nikah'] as $field) {
                if ($request->hasFile($field)) {
                    $this->deleteFile($pengajuan->formAktaLahir?->$field);
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/akta-lahir');
                }
            }

            $pengajuan->formAktaLahir()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            $this->handleResubmit($pengajuan);

            return response()->json([
                'message' => 'Pengajuan Akta Kelahiran berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->fresh()->load(['user', 'formAktaLahir'])),
            ]);
        });
    }
}
