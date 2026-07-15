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
                        'jenis_perbaikan_id', 'nama_kepala_keluarga',
                        'nomor_kk', 'nama_anggota_yang_diperbaiki', 'data_perbaikan', 'file_pendukung',
                    ],
                    properties: [
                        // Data spesifik KK Perbaikan
                        new OA\Property(property: 'jenis_perbaikan_id', type: 'integer', example: 1),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk', type: 'string'),
                        new OA\Property(property: 'nama_anggota_yang_diperbaiki', type: 'string'),
                        new OA\Property(
                            property: 'data_perbaikan',
                            type: 'string',
                            description: 'JSON string key-value data yang perlu diperbaiki',
                            example: '{"nama_lama":"Budi","nama_baru":"Budi Santoso"}',
                        ),
                        new OA\Property(
                            property: 'file_pendukung',
                            type: 'array',
                            items: new OA\Items(type: 'string', format: 'binary'),
                            description: 'Satu atau lebih file pendukung. Kirim dengan field name file_pendukung[] untuk multiple files.'
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

            $pengajuan = Pengajuan::create($this->buildPengajuanData($user, 'kk_perbaikan'));

            // Simpan setiap file pendukung, hasilnya array of paths
            // Cek file_pendukung[] (bracket) lalu file_pendukung (tanpa bracket)
            $files = $request->file('file_pendukung[]')
                ?? $request->file('file_pendukung')
                ?? [];
            if (!is_array($files)) {
                $files = $files ? [$files] : [];
            }
            $filePaths = collect($files)
                ->filter()
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

    #[OA\Post(
        path: '/pengajuan/kk-perbaikan/{id}',
        summary: 'Revisi pengajuan KK Perbaikan',
        description: 'Mengupdate data form dan/atau dokumen pengajuan KK Perbaikan. Jika file_pendukung dikirim, semua file lama akan digantikan.',
        tags: ['Pengajuan - KK Perbaikan'],
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
                        new OA\Property(property: 'jenis_perbaikan_id', type: 'integer'),
                        new OA\Property(property: 'nama_kepala_keluarga', type: 'string'),
                        new OA\Property(property: 'nomor_kk', type: 'string'),
                        new OA\Property(property: 'nama_anggota_yang_diperbaiki', type: 'string'),
                        new OA\Property(
                            property: 'data_perbaikan',
                            type: 'string',
                            description: 'JSON string key-value data yang perlu diperbaiki',
                            example: '{"nama_lama":"Budi","nama_baru":"Budi Santoso"}',
                        ),
                        new OA\Property(
                            property: 'file_pendukung',
                            type: 'array',
                            items: new OA\Items(type: 'string', format: 'binary'),
                            description: 'Satu atau lebih file pendukung. Jika dikirim, semua file lama diganti.'
                        ),
                    ]
                )
            )
        ),
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
        $this->authorizeCanUpdate($pengajuan);

        return DB::transaction(function () use ($request, $pengajuan) {
            $formData = $request->only([
                'jenis_perbaikan_id', 'nama_kepala_keluarga', 'nomor_kk',
                'nama_anggota_yang_diperbaiki', 'data_perbaikan',
            ]);

            if ($request->hasFile('file_pendukung[]') || $request->hasFile('file_pendukung')) {
                // Hapus file lama jika ada
                $fileLama = $pengajuan->formKkPerbaikan?->file_pendukung ?? [];
                foreach ((array) $fileLama as $path) {
                    $this->deleteFile($path);
                }
                $files = $request->file('file_pendukung[]')
                    ?? $request->file('file_pendukung')
                    ?? [];
                if (!is_array($files)) {
                    $files = $files ? [$files] : [];
                }
                $formData['file_pendukung'] = collect($files)
                    ->filter()
                    ->map(fn($file) => $this->storeFile($file, 'pengajuan/kk-perbaikan'))
                    ->values()
                    ->all();
            }

            $pengajuan->formKkPerbaikan()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            $this->handleResubmit($pengajuan);

            return response()->json([
                'message' => 'Pengajuan KK Perbaikan berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->fresh()->load(['user', 'formKkPerbaikan'])),
            ]);
        });
    }
}
