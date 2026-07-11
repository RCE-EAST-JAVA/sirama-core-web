<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'SIRAMA API',
    version: '1.0.0',
    description: 'API untuk aplikasi mobile SIRAMA (Sistem Informasi Administrasi Kependudukan). Autentikasi menggunakan Laravel Sanctum Bearer Token.',
    contact: new OA\Contact(email: 'admin@sirama.id')
)]
#[OA\Server(url: '/api', description: 'API Server')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Masukkan token Sanctum. Contoh: Bearer {token}'
)]
#[OA\Tag(name: 'Auth', description: 'Autentikasi warga')]
#[OA\Tag(name: 'Pengajuan', description: 'Manajemen pengajuan layanan kependudukan')]

// --- Response Schemas ---

#[OA\Schema(
    schema: 'UserResponse',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nik', type: 'string', example: '3277010101900001'),
        new OA\Property(property: 'name', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
        new OA\Property(property: 'role', type: 'string', example: 'warga'),
        new OA\Property(property: 'desa', type: 'string', nullable: true, example: null),
    ]
)]
#[OA\Schema(
    schema: 'AuthResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Login berhasil.'),
        new OA\Property(property: 'token', type: 'string', example: '1|abc123xyz'),
        new OA\Property(property: 'user', ref: '#/components/schemas/UserResponse'),
    ]
)]
#[OA\Schema(
    schema: 'RiwayatStatusResponse',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'diverifikasi_desa'),
        new OA\Property(property: 'catatan', type: 'string', nullable: true, example: 'Berkas lengkap'),
        new OA\Property(property: 'waktu', type: 'string', format: 'date-time', example: '2026-01-01T08:00:00+07:00'),
    ]
)]
#[OA\Schema(
    schema: 'PengajuanResponse',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'jenis_layanan', type: 'string', example: 'kia'),
        new OA\Property(property: 'label_layanan', type: 'string', example: 'KIA (Kartu Identitas Anak)'),
        new OA\Property(
            property: 'status',
            type: 'string',
            enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diproses_kecamatan', 'selesai'],
            example: 'berkas_diterima'
        ),
        new OA\Property(property: 'label_status', type: 'string', example: 'Berkas Diterima'),
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
        new OA\Property(property: 'lokasi_dokumen', type: 'string', nullable: true, example: null),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'user', ref: '#/components/schemas/UserResponse'),
        new OA\Property(
            property: 'riwayat_statuses',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/RiwayatStatusResponse')
        ),
    ]
)]
#[OA\Schema(
    schema: 'PengajuanListResponse',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PengajuanResponse')
        ),
        new OA\Property(
            property: 'meta',
            properties: [
                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                new OA\Property(property: 'last_page', type: 'integer', example: 3),
                new OA\Property(property: 'total', type: 'integer', example: 25),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The nik field is required.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            example: ['nik' => ['NIK wajib diisi.'], 'password' => ['Password wajib diisi.']]
        ),
    ]
)]
#[OA\Schema(
    schema: 'MessageResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Operasi berhasil.'),
    ]
)]

// --- Form Schemas per Jenis Layanan ---

#[OA\Schema(
    schema: 'FormKia',
    required: ['nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'nama_kepala_keluarga', 'agama', 'kewarganegaraan'],
    properties: [
        new OA\Property(property: 'nama_lengkap', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'tempat_lahir', type: 'string', example: 'Bandung'),
        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '2020-01-15'),
        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P'], example: 'L'),
        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
        new OA\Property(property: 'kewarganegaraan', type: 'string', example: 'WNI'),
    ],
    description: 'Form untuk jenis layanan: kia'
)]
#[OA\Schema(
    schema: 'Form3In1',
    required: ['nama_lengkap_pemohon', 'desa', 'alamat_lengkap', 'nama_anak', 'tanggal_lahir_anak'],
    properties: [
        new OA\Property(property: 'nama_lengkap_pemohon', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'desa', type: 'string', example: 'Cibabat'),
        new OA\Property(property: 'alamat_lengkap', type: 'string', example: 'Jl. Merdeka No. 1'),
        new OA\Property(property: 'nama_anak', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'tanggal_lahir_anak', type: 'string', format: 'date', example: '2026-01-01'),
    ],
    description: 'Form untuk jenis layanan: 3_in_1 (Akta + KK + KIA)'
)]
#[OA\Schema(
    schema: 'FormKkPenambahan',
    required: ['nama_kepala_keluarga', 'nomor_kk', 'alamat', 'rt', 'rw', 'nama_lengkap_tambahan', 'jenis_kelamin_tambahan', 'tempat_lahir_tambahan', 'tanggal_lahir_tambahan', 'status_hubungan', 'agama', 'nama_ibu_kandung', 'nik_ibu', 'nama_ayah_kandung', 'nik_ayah'],
    properties: [
        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'nomor_kk', type: 'string', example: '3277011234567890'),
        new OA\Property(property: 'alamat', type: 'string', example: 'Jl. Merdeka No. 1'),
        new OA\Property(property: 'nama_dusun', type: 'string', nullable: true, example: 'Dusun Mawar'),
        new OA\Property(property: 'rt', type: 'string', example: '001'),
        new OA\Property(property: 'rw', type: 'string', example: '002'),
        new OA\Property(property: 'nama_ketua_rt', type: 'string', nullable: true, example: 'Ahmad'),
        new OA\Property(property: 'nama_ketua_rw', type: 'string', nullable: true, example: 'Hasan'),
        new OA\Property(property: 'nama_lengkap_tambahan', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'jenis_kelamin_tambahan', type: 'string', enum: ['L', 'P'], example: 'L'),
        new OA\Property(property: 'tempat_lahir_tambahan', type: 'string', example: 'Bandung'),
        new OA\Property(property: 'tanggal_lahir_tambahan', type: 'string', format: 'date', example: '2020-01-01'),
        new OA\Property(property: 'status_hubungan', type: 'string', example: 'Anak'),
        new OA\Property(property: 'kelainan_fisik_mental', type: 'string', nullable: true, example: null),
        new OA\Property(property: 'penyandang_cacat', type: 'string', nullable: true, example: null),
        new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
        new OA\Property(property: 'nama_ibu_kandung', type: 'string', example: 'Siti Aminah'),
        new OA\Property(property: 'nik_ibu', type: 'string', example: '3277010101900002'),
        new OA\Property(property: 'nama_ayah_kandung', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'nik_ayah', type: 'string', example: '3277010101900001'),
    ],
    description: 'Form untuk jenis layanan: kk_penambahan'
)]
#[OA\Schema(
    schema: 'FormKkPengurangan',
    required: ['alasan_pengurangan', 'nama_lengkap_anggota', 'alamat_lengkap_anggota', 'nik_anggota'],
    properties: [
        new OA\Property(property: 'alasan_pengurangan', type: 'string', example: 'Pindah domisili ke luar kota'),
        new OA\Property(property: 'nama_lengkap_anggota', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'alamat_lengkap_anggota', type: 'string', example: 'Jl. Merdeka No. 1, Bandung'),
        new OA\Property(property: 'nik_anggota', type: 'string', example: '3277010101900003'),
    ],
    description: 'Form untuk jenis layanan: kk_pengurangan'
)]
#[OA\Schema(
    schema: 'FormKkPerbaikan',
    required: ['jenis_perbaikan_id', 'nama_kepala_keluarga', 'nomor_kk', 'nama_anggota_yang_diperbaiki', 'data_perbaikan'],
    properties: [
        new OA\Property(property: 'jenis_perbaikan_id', type: 'integer', example: 1, description: 'ID dari master_jenis_perbaikan_kks'),
        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'nomor_kk', type: 'string', example: '3277011234567890'),
        new OA\Property(property: 'nama_anggota_yang_diperbaiki', type: 'string', example: 'Anak Budi'),
        new OA\Property(
            property: 'data_perbaikan',
            type: 'object',
            example: ['nama_lama' => 'Anakk Budi', 'nama_baru' => 'Anak Budi'],
            description: 'Key-value data yang perlu diperbaiki'
        ),
    ],
    description: 'Form untuk jenis layanan: kk_perbaikan'
)]

// --- Upload Dokumen per Jenis Layanan ---

#[OA\Schema(
    schema: 'UploadDokumenKia',
    description: 'Field dokumen untuk jenis layanan kia',
    properties: [
        new OA\Property(property: 'field', type: 'string', enum: ['file_akta_kelahiran', 'file_kk', 'file_surat_nikah', 'file_foto_anak'], example: 'file_kk'),
        new OA\Property(property: 'dokumen', type: 'string', format: 'binary'),
    ]
)]
#[OA\Schema(
    schema: 'UploadDokumen3In1',
    description: 'Field dokumen untuk jenis layanan 3_in_1',
    properties: [
        new OA\Property(property: 'field', type: 'string', enum: ['file_sk_lahir', 'file_kk', 'file_ktp_ortu', 'file_surat_nikah', 'file_foto_anak'], example: 'file_sk_lahir'),
        new OA\Property(property: 'dokumen', type: 'string', format: 'binary'),
    ]
)]
#[OA\Schema(
    schema: 'UploadDokumenKkPenambahan',
    description: 'Field dokumen untuk jenis layanan kk_penambahan',
    properties: [
        new OA\Property(property: 'field', type: 'string', enum: ['file_kk_asli', 'file_sk_lahir_akta', 'file_ktp_suami_istri', 'file_surat_nikah'], example: 'file_kk_asli'),
        new OA\Property(property: 'dokumen', type: 'string', format: 'binary'),
    ]
)]
#[OA\Schema(
    schema: 'UploadDokumenKkPengurangan',
    description: 'Field dokumen untuk jenis layanan kk_pengurangan',
    properties: [
        new OA\Property(property: 'field', type: 'string', enum: ['file_kk_asli', 'file_ktp_asli', 'file_sk_pindah_mati'], example: 'file_kk_asli'),
        new OA\Property(property: 'dokumen', type: 'string', format: 'binary'),
    ]
)]
#[OA\Schema(
    schema: 'UploadDokumenKkPerbaikan',
    description: 'Field dokumen untuk jenis layanan kk_perbaikan (bisa multiple)',
    properties: [
        new OA\Property(property: 'field', type: 'string', enum: ['file_pendukung'], example: 'file_pendukung'),
        new OA\Property(property: 'dokumen', type: 'string', format: 'binary'),
    ]
)]

// --- Submit Form (satu endpoint, request body menyesuaikan jenis_layanan) ---

#[OA\Post(
    path: '/pengajuan/{pengajuan}/form',
    operationId: 'submitForm',
    summary: 'Submit data form pengajuan',
    description: "Isi data form sesuai **jenis_layanan** pengajuan. Pilih schema yang sesuai:\n\n| jenis_layanan | Schema |\n|---|---|\n| `kia` | FormKia |\n| `3_in_1` | Form3In1 |\n| `kk_penambahan` | FormKkPenambahan |\n| `kk_pengurangan` | FormKkPengurangan |\n| `kk_perbaikan` | FormKkPerbaikan |\n\nSetelah form diisi, upload dokumen via `POST /pengajuan/{id}/dokumen`.",
    tags: ['Pengajuan'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'pengajuan', in: 'path', required: true, description: 'ID pengajuan', schema: new OA\Schema(type: 'integer', example: 1)),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            oneOf: [
                new OA\Schema(ref: '#/components/schemas/FormKia'),
                new OA\Schema(ref: '#/components/schemas/Form3In1'),
                new OA\Schema(ref: '#/components/schemas/FormKkPenambahan'),
                new OA\Schema(ref: '#/components/schemas/FormKkPengurangan'),
                new OA\Schema(ref: '#/components/schemas/FormKkPerbaikan'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Data form berhasil disimpan', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
        new OA\Response(response: 401, description: 'Unauthenticated'),
        new OA\Response(response: 403, description: 'Forbidden'),
        new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')),
    ]
)]
class ApiDocController extends Controller
{
    // Hanya untuk OpenAPI annotations
}
