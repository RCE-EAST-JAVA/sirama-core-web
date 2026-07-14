<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Requests\Api\StoreKkPenguranganRequest;
use App\Http\Resources\PengajuanResource;
use App\Models\FormKkPengurangan;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class KkPenguranganPengajuanController extends BasePengajuanController
{
    #[OA\Post(
        path: '/pengajuan/kk-pengurangan',
        summary: 'Buat pengajuan pengurangan anggota KK',
        description: 'Membuat pengajuan pengurangan anggota KK sekaligus mengisi form dan mengupload semua dokumen dalam satu request.',
        tags: ['Pengajuan - KK Pengurangan'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: [
                        'alasan_pengurangan', 'nama_lengkap_anggota', 'alamat_lengkap_anggota', 'nik_anggota',
                        'file_kk_asli', 'file_ktp_asli', 'file_sk_pindah_mati',
                    ],
                    properties: [
                        // Data spesifik KK Pengurangan (data anggota yang dikurangi)
                        new OA\Property(property: 'alasan_pengurangan', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_anggota', type: 'string'),
                        new OA\Property(property: 'alamat_lengkap_anggota', type: 'string'),
                        new OA\Property(property: 'nik_anggota', type: 'string', example: '3201234567890001'),
                        new OA\Property(property: 'file_kk_asli', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_asli', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_sk_pindah_mati', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Pengajuan KK Pengurangan berhasil dibuat'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreKkPenguranganRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $pengajuan = Pengajuan::create($this->buildPengajuanData($user, 'kk_pengurangan'));

            FormKkPengurangan::create([
                'pengajuan_id'           => $pengajuan->id,
                'alasan_pengurangan'     => $request->alasan_pengurangan,
                'nama_lengkap_anggota'   => $request->nama_lengkap_anggota,
                'alamat_lengkap_anggota' => $request->alamat_lengkap_anggota,
                'nik_anggota'            => $request->nik_anggota,
                'file_kk_asli'           => $this->storeFile($request->file('file_kk_asli'), 'pengajuan/kk-pengurangan'),
                'file_ktp_asli'          => $this->storeFile($request->file('file_ktp_asli'), 'pengajuan/kk-pengurangan'),
                'file_sk_pindah_mati'    => $this->storeFile($request->file('file_sk_pindah_mati'), 'pengajuan/kk-pengurangan'),
            ]);

            return response()->json([
                'message' => 'Pengajuan KK Pengurangan berhasil dibuat.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPengurangan'])),
            ], 201);
        });
    }

    #[OA\Post(
        path: '/pengajuan/kk-pengurangan/{id}',
        summary: 'Revisi pengajuan KK Pengurangan',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KK Pengurangan. File yang tidak dikirim tidak akan diubah.',
        tags: ['Pengajuan - KK Pengurangan'],
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
                        new OA\Property(property: 'alasan_pengurangan', type: 'string'),
                        new OA\Property(property: 'nama_lengkap_anggota', type: 'string'),
                        new OA\Property(property: 'alamat_lengkap_anggota', type: 'string'),
                        new OA\Property(property: 'nik_anggota', type: 'string'),
                        new OA\Property(property: 'file_kk_asli', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_ktp_asli', type: 'string', format: 'binary'),
                        new OA\Property(property: 'file_sk_pindah_mati', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Pengajuan KK Pengurangan berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(StoreKkPenguranganRequest $request, Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $formData = $request->only([
                'alasan_pengurangan', 'nama_lengkap_anggota',
                'alamat_lengkap_anggota', 'nik_anggota',
            ]);

            foreach (['file_kk_asli', 'file_ktp_asli', 'file_sk_pindah_mati'] as $field) {
                if ($request->hasFile($field)) {
                    $this->deleteFile($pengajuan->formKkPengurangan?->$field);
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/kk-pengurangan');
                }
            }

            $pengajuan->formKkPengurangan()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan KK Pengurangan berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formKkPengurangan'])),
            ]);
        });
    }
}
