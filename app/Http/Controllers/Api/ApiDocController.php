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
#[OA\Server(
    url: 'https://sirama.tunggulmajid.my.id/api',
    description: 'SIRAMA API Server (Production)'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000/api',
    description: 'SIRAMA API Server (Local)'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Masukkan token Sanctum. Contoh: Bearer {token}'
)]
#[OA\Tag(name: 'Auth', description: 'Autentikasi warga')]
#[OA\Tag(name: 'Pengajuan', description: 'Endpoint umum pengajuan (list, detail, status)')]
#[OA\Tag(name: 'Pengajuan - KIA', description: 'Pengajuan Kartu Identitas Anak')]
#[OA\Tag(name: 'Pengajuan - 3 in 1', description: 'Pengajuan 3 in 1 (Akta Kelahiran + KK + KIA)')]
#[OA\Tag(name: 'Pengajuan - KK Penambahan', description: 'Pengajuan penambahan anggota KK')]
#[OA\Tag(name: 'Pengajuan - KK Pengurangan', description: 'Pengajuan pengurangan anggota KK')]
#[OA\Tag(name: 'Pengajuan - KK Perbaikan', description: 'Pengajuan perbaikan data KK')]

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
            enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diverifikasi_kecamatan', 'selesai'],
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

// --- Request Schemas per Jenis Layanan (multipart/form-data) ---
// Semua field data + dokumen dikirim sekaligus dalam satu request POST.

#[OA\Schema(
    schema: 'RequestKia',
    description: 'Request body untuk POST /pengajuan/kia dan PUT /pengajuan/kia/{id}. Gunakan multipart/form-data.',
    required: ['no_whatsapp', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'nama_kepala_keluarga', 'agama', 'kewarganegaraan', 'file_akta_kelahiran', 'file_kk', 'file_surat_nikah', 'file_foto_anak'],
    properties: [
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789', description: 'Nomor WhatsApp untuk notifikasi'),
        new OA\Property(property: 'nama_lengkap', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'tempat_lahir', type: 'string', example: 'Bandung'),
        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '2020-01-15'),
        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P'], example: 'L'),
        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
        new OA\Property(property: 'kewarganegaraan', type: 'string', example: 'WNI'),
        new OA\Property(property: 'file_akta_kelahiran', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_kk', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
    ]
)]
#[OA\Schema(
    schema: 'RequestTiga1',
    description: 'Request body untuk POST /pengajuan/3-in-1 dan PUT /pengajuan/3-in-1/{id}. Gunakan multipart/form-data.',
    required: ['no_whatsapp', 'nama_lengkap_pemohon', 'desa', 'alamat_lengkap', 'nama_anak', 'tanggal_lahir_anak', 'file_sk_lahir', 'file_kk', 'file_ktp_ortu', 'file_surat_nikah', 'file_foto_anak'],
    properties: [
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
        new OA\Property(property: 'nama_lengkap_pemohon', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'desa', type: 'string', example: 'Cibabat'),
        new OA\Property(property: 'alamat_lengkap', type: 'string', example: 'Jl. Merdeka No. 1'),
        new OA\Property(property: 'nama_anak', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'tanggal_lahir_anak', type: 'string', format: 'date', example: '2026-01-01'),
        new OA\Property(property: 'file_sk_lahir', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_kk', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_ktp_ortu', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_foto_anak', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
    ]
)]
#[OA\Schema(
    schema: 'RequestKkPenambahan',
    description: 'Request body untuk POST /pengajuan/kk-penambahan dan PUT /pengajuan/kk-penambahan/{id}. Gunakan multipart/form-data.',
    required: [
        'no_whatsapp', 'nama_kepala_keluarga', 'nomor_kk', 'alamat', 'nama_dusun',
        'rt', 'rw', 'nama_ketua_rt', 'nama_ketua_rw', 'nama_lengkap_tambahan',
        'jenis_kelamin_tambahan', 'tempat_lahir_tambahan', 'tanggal_lahir_tambahan',
        'status_hubungan', 'kelainan_fisik_mental', 'penyandang_cacat', 'agama',
        'nama_ibu_kandung', 'nik_ibu', 'nama_ayah_kandung', 'nik_ayah',
        'file_kk_asli', 'file_sk_lahir_akta', 'file_ktp_suami_istri', 'file_surat_nikah',
    ],
    properties: [
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
        new OA\Property(property: 'nama_kepala_keluarga', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'nomor_kk', type: 'string', example: '3277011234567890'),
        new OA\Property(property: 'alamat', type: 'string', example: 'Jl. Merdeka No. 1'),
        new OA\Property(property: 'nama_dusun', type: 'string', example: 'Dusun Mawar'),
        new OA\Property(property: 'rt', type: 'string', example: '001'),
        new OA\Property(property: 'rw', type: 'string', example: '002'),
        new OA\Property(property: 'nama_ketua_rt', type: 'string', example: 'Ahmad'),
        new OA\Property(property: 'nama_ketua_rw', type: 'string', example: 'Hasan'),
        new OA\Property(property: 'nama_lengkap_tambahan', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'jenis_kelamin_tambahan', type: 'string', enum: ['L', 'P'], example: 'L'),
        new OA\Property(property: 'tempat_lahir_tambahan', type: 'string', example: 'Bandung'),
        new OA\Property(property: 'tanggal_lahir_tambahan', type: 'string', format: 'date', example: '2020-01-01'),
        new OA\Property(property: 'status_hubungan', type: 'string', example: 'Anak'),
        new OA\Property(property: 'kelainan_fisik_mental', type: 'string', example: 'Tidak Ada'),
        new OA\Property(property: 'penyandang_cacat', type: 'string', example: 'Tidak'),
        new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
        new OA\Property(property: 'nama_ibu_kandung', type: 'string', example: 'Siti Aminah'),
        new OA\Property(property: 'nik_ibu', type: 'string', example: '3277010101900002'),
        new OA\Property(property: 'nama_ayah_kandung', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'nik_ayah', type: 'string', example: '3277010101900001'),
        new OA\Property(property: 'file_kk_asli', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_sk_lahir_akta', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_ktp_suami_istri', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_surat_nikah', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
    ]
)]
#[OA\Schema(
    schema: 'RequestKkPengurangan',
    description: 'Request body untuk POST /pengajuan/kk-pengurangan dan PUT /pengajuan/kk-pengurangan/{id}. Gunakan multipart/form-data.',
    required: ['no_whatsapp', 'alasan_pengurangan', 'nama_lengkap_anggota', 'alamat_lengkap_anggota', 'nik_anggota', 'file_kk_asli', 'file_ktp_asli', 'file_sk_pindah_mati'],
    properties: [
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
        new OA\Property(property: 'alasan_pengurangan', type: 'string', example: 'Pindah domisili ke luar kota'),
        new OA\Property(property: 'nama_lengkap_anggota', type: 'string', example: 'Anak Budi'),
        new OA\Property(property: 'alamat_lengkap_anggota', type: 'string', example: 'Jl. Merdeka No. 1, Bandung'),
        new OA\Property(property: 'nik_anggota', type: 'string', example: '3277010101900003'),
        new OA\Property(property: 'file_kk_asli', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_ktp_asli', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
        new OA\Property(property: 'file_sk_pindah_mati', type: 'string', format: 'binary', description: 'jpg/png/pdf, maks 5MB'),
    ]
)]
#[OA\Schema(
    schema: 'RequestKkPerbaikan',
    description: 'Request body untuk POST /pengajuan/kk-perbaikan dan PUT /pengajuan/kk-perbaikan/{id}. Gunakan multipart/form-data. file_pendukung adalah array, bisa lebih dari satu file.',
    required: ['no_whatsapp', 'jenis_perbaikan_id', 'nama_kepala_keluarga', 'nomor_kk', 'nama_anggota_yang_diperbaiki', 'data_perbaikan', 'file_pendukung'],
    properties: [
        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
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
        new OA\Property(
            property: 'file_pendukung[]',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'binary'),
            description: 'Satu atau lebih file pendukung, jpg/png/pdf maks 5MB per file'
        ),
    ]
)]
class ApiDocController extends Controller
{
    // Hanya untuk OpenAPI annotations
}
