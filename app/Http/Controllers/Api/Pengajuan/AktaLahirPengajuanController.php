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
                        'nama_lengkap', 'nik', 'no_whatsapp', 'tanggal_lahir', 'jenis_kelamin',
                        'alamat', 'desa', 'rt', 'rw',
                        'nama_anak', 'tanggal_lahir_anak',
                        'file_sk_lahir', 'file_kk', 'file_ktp_ayah', 'file_ktp_ibu', 'file_surat_nikah',
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

            $pengajuan = Pengajuan::create([
                'user_id'       => $user->id,
                'jenis_layanan' => 'akta_kelahiran',
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

        return DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'no_whatsapp'   => $request->no_whatsapp   ?? $pengajuan->no_whatsapp,
                'nama_lengkap'  => $request->nama_lengkap  ?? $pengajuan->nama_lengkap,
                'nik'           => $request->nik            ?? $pengajuan->nik,
                'tanggal_lahir' => $request->tanggal_lahir ?? $pengajuan->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin ?? $pengajuan->jenis_kelamin,
                'pekerjaan'     => $request->pekerjaan     ?? $pengajuan->pekerjaan,
                'alamat'        => $request->alamat         ?? $pengajuan->alamat,
                'desa'          => $request->desa           ?? $pengajuan->desa,
                'rt'            => $request->rt             ?? $pengajuan->rt,
                'rw'            => $request->rw             ?? $pengajuan->rw,
            ]);

            $formData = $request->only(['nama_anak', 'tanggal_lahir_anak']);

            foreach (['file_sk_lahir', 'file_kk', 'file_ktp_ayah', 'file_ktp_ibu', 'file_surat_nikah'] as $field) {
                if ($request->hasFile($field)) {
                    $formData[$field] = $this->storeFile($request->file($field), 'pengajuan/akta-lahir');
                }
            }

            $pengajuan->formAktaLahir()->updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                $formData
            );

            return response()->json([
                'message' => 'Pengajuan Akta Kelahiran berhasil diupdate.',
                'data'    => new PengajuanResource($pengajuan->load(['user', 'formAktaLahir'])),
            ]);
        });
    }
}
