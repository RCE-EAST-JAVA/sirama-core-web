# SIRAMA API Documentation

Dokumentasi API untuk pengembangan aplikasi mobile SIRAMA.  
Base URL: `http://{domain}/api`  
Autentikasi: **Laravel Sanctum** (Bearer Token)

---

## Daftar Isi

- [Konvensi Umum](#konvensi-umum)
- [Alur Penggunaan](#alur-penggunaan)
- [Auth](#auth)
- [Desa](#desa)
- [Profile](#profile)
- [Pengajuan](#pengajuan)
  - [List & Detail](#list--detail-pengajuan)
  - [KIA](#kia---kartu-identitas-anak)
  - [3 in 1](#3-in-1---akta-kelahiran--kk--kia)
  - [KK Penambahan](#kk-penambahan)
  - [KK Pengurangan](#kk-pengurangan)
  - [KK Perbaikan](#kk-perbaikan)
  - [Akta Kelahiran](#akta-kelahiran)
  - [Akta Kematian](#akta-kematian)
- [Status Pengajuan](#status-pengajuan)
- [Kode Error](#kode-error)

---

## Konvensi Umum

### Headers

Untuk semua endpoint yang memerlukan autentikasi:
```
Authorization: Bearer {token}
Accept: application/json
```

Untuk request dengan upload file:
```
Content-Type: multipart/form-data
```

Untuk request JSON biasa:
```
Content-Type: application/json
```

### Format Response Sukses

```json
{
  "message": "Pesan sukses",
  "data": { ... }
}
```

### Format Response Error Validasi (422)

```json
{
  "message": "The nik field is required.",
  "errors": {
    "nik": ["The nik field is required."],
    "password": ["The password field must be at least 8 characters."]
  }
}
```

### Format Response Error Umum

```json
{
  "message": "Unauthenticated."
}
```

---

## Alur Penggunaan

### Alur Registrasi & Login

```
1. GET  /api/desas          → Ambil daftar desa (untuk dropdown)
2. POST /api/auth/register  → Daftar akun baru, simpan token yang dikembalikan
3. POST /api/auth/login     → Login, simpan token yang dikembalikan
```

### Alur Membuat Pengajuan

```
1. POST /api/auth/login                     → Dapatkan token
2. GET  /api/profile                        → (Opsional) Ambil data profil untuk pre-fill form
3. POST /api/pengajuan/{jenis-layanan}      → Buat pengajuan baru dengan upload dokumen
4. GET  /api/pengajuan/{id}/status          → Pantau status pengajuan
5. POST /api/pengajuan/{jenis-layanan}/{id} → (Jika ditolak) Revisi dan kirim ulang
```

### Alur Status Pengajuan

```
berkas_diterima → diverifikasi_desa → diproses_kecamatan → selesai
                ↘ ditolak_desa
                                    ↘ ditolak_kecamatan
```

Jika ditolak (`ditolak_desa` atau `ditolak_kecamatan`), warga bisa revisi dan update pengajuan menggunakan endpoint update.

---

## Auth

### POST /api/auth/register

Registrasi warga baru. Mengembalikan token Sanctum.

**Request Body (JSON)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nik` | string | Ya | 16 digit NIK, harus unik |
| `name` | string | Ya | Nama lengkap |
| `no_whatsapp` | string | Ya | Nomor WhatsApp aktif |
| `password` | string | Ya | Min 8 karakter |
| `password_confirmation` | string | Ya | Harus sama dengan `password` |

**Contoh Request**
```json
{
  "nik": "3277010101900001",
  "name": "Budi Santoso",
  "no_whatsapp": "08123456789",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response 201**
```json
{
  "message": "Registrasi berhasil.",
  "token": "1|abc123xyz...",
  "user": {
    "id": 10,
    "name": "Budi Santoso",
    "nik": "3277010101900001",
    "no_whatsapp": "08123456789",
    "role": "warga"
  }
}
```

---

### POST /api/auth/login

Login dengan NIK dan password.

**Request Body (JSON)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nik` | string | Ya | 16 digit NIK |
| `password` | string | Ya | Password akun |

**Contoh Request**
```json
{
  "nik": "3277010101900001",
  "password": "password123"
}
```

**Response 200**
```json
{
  "message": "Login berhasil.",
  "token": "2|def456uvw...",
  "user": {
    "id": 10,
    "name": "Budi Santoso",
    "nik": "3277010101900001",
    "no_whatsapp": "08123456789",
    "role": "warga"
  }
}
```

**Response 422** — NIK tidak ditemukan atau password salah
```json
{
  "message": "NIK atau password salah."
}
```

---

### POST /api/auth/logout

Logout dan hapus token aktif.  
**Memerlukan token.**

**Response 200**
```json
{
  "message": "Logout berhasil."
}
```

---

## Desa

### GET /api/desas

Ambil daftar semua desa. **Tidak perlu token.**  
Gunakan untuk mengisi dropdown pilihan desa saat registrasi atau pengajuan.

**Response 200**
```json
{
  "data": [
    { "id": 1, "nama": "Desa Kerang", "kecamatan": "Sukosari" },
    { "id": 2, "nama": "Desa Nogosari", "kecamatan": "Sukosari" },
    { "id": 3, "nama": "Desa Sukosari Lor", "kecamatan": "Sukosari" }
  ]
}
```

---

## Profile

### GET /api/profile

Ambil data profil user yang sedang login.  
**Memerlukan token.**

**Response 200**
```json
{
  "data": {
    "id": 10,
    "name": "Budi Santoso",
    "nik": "3277010101900001",
    "no_whatsapp": "08123456789",
    "tanggal_lahir": "1990-01-01",
    "jenis_kelamin": "L",
    "pekerjaan": "Wiraswasta",
    "alamat": "Jl. Merdeka No. 1",
    "desa": "Desa Sukosari Lor",
    "rt": "001",
    "rw": "002",
    "foto_profil": "http://{domain}/storage/foto-profil/abc.jpg",
    "role": "warga"
  }
}
```

> **Tips Mobile:** Gunakan data profil ini untuk pre-fill form pengajuan agar pengguna tidak perlu mengisi ulang data diri setiap kali mengajukan layanan.

---

### POST /api/profile

Update data profil. Semua field opsional — hanya kirim field yang berubah.  
**Memerlukan token.**  
**Content-Type: multipart/form-data**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `name` | string | Tidak | Nama lengkap |
| `no_whatsapp` | string | Tidak | Nomor WhatsApp |
| `tanggal_lahir` | date | Tidak | Format `Y-m-d` |
| `jenis_kelamin` | string | Tidak | `L` atau `P` |
| `pekerjaan` | string | Tidak | |
| `alamat` | string | Tidak | |
| `desa` | string | Tidak | |
| `rt` | string | Tidak | |
| `rw` | string | Tidak | |
| `foto_profil` | file | Tidak | jpg/png, max 2MB |
| `password` | string | Tidak | Min 8 karakter (jika ingin ganti password) |
| `password_confirmation` | string | Tidak | Wajib jika `password` diisi |

**Response 200**
```json
{
  "message": "Profil berhasil diupdate.",
  "data": { ... }
}
```

---

## Pengajuan

### List & Detail Pengajuan

#### GET /api/pengajuan

Ambil semua pengajuan milik user yang login, diurutkan terbaru.  
**Memerlukan token.**

**Query Parameters (Opsional)**

| Parameter | Tipe | Keterangan |
|-----------|------|------------|
| `status` | string | Filter status: `berkas_diterima`, `ditolak_desa`, `diverifikasi_desa`, `ditolak_kecamatan`, `diproses_kecamatan`, `selesai` |

**Contoh:** `GET /api/pengajuan?status=berkas_diterima`

**Response 200**
```json
{
  "data": [
    {
      "id": 1,
      "jenis_layanan": "kia",
      "status": "berkas_diterima",
      "no_whatsapp": "08123456789",
      "created_at": "2026-07-14T10:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "total": 25
  }
}
```

---

#### GET /api/pengajuan/{id}

Ambil detail satu pengajuan beserta data form dan riwayat status.  
**Memerlukan token.** Hanya bisa akses pengajuan milik sendiri.

**Response 200**
```json
{
  "data": {
    "id": 1,
    "jenis_layanan": "kia",
    "status": "diverifikasi_desa",
    "no_whatsapp": "08123456789",
    "nama_lengkap": "Budi Santoso",
    "nik": "3277010101900001",
    "tanggal_lahir": "1990-01-01",
    "jenis_kelamin": "L",
    "pekerjaan": "Wiraswasta",
    "alamat": "Jl. Merdeka No. 1",
    "desa": "Desa Sukosari Lor",
    "rt": "001",
    "rw": "002",
    "form": { ... },
    "riwayat": [ ... ],
    "created_at": "2026-07-14T10:00:00.000000Z"
  }
}
```

**Response 403** — Pengajuan bukan milik user ini  
**Response 404** — Pengajuan tidak ditemukan

---

#### GET /api/pengajuan/{id}/status

Cek status terkini sebuah pengajuan (lebih ringan dari `/show`).  
**Memerlukan token.**

**Response 200**
```json
{
  "status": "diproses_kecamatan",
  "label_status": "Diproses Kecamatan",
  "lokasi_dokumen": null
}
```

> Saat status `selesai`, field `lokasi_dokumen` akan berisi path/URL dokumen hasil yang bisa didownload.

---

### Data Diri Pemohon (Semua Endpoint Pengajuan)

Semua endpoint pembuatan pengajuan menerima **data diri pemohon** berikut. Jika field ini tidak dikirim, sistem akan menggunakan data dari profil user yang login.

| Field | Tipe | Keterangan |
|-------|------|------------|
| `nama_lengkap` | string | Nama lengkap pemohon |
| `nik` | string | 16 digit NIK pemohon |
| `no_whatsapp` | string | Nomor WhatsApp aktif |
| `tanggal_lahir` | date | Format `Y-m-d` |
| `jenis_kelamin` | string | `L` atau `P` |
| `pekerjaan` | string | (Opsional) |
| `alamat` | string | Alamat lengkap |
| `desa` | string | Nama desa |
| `rt` | string | Nomor RT |
| `rw` | string | Nomor RW |

---

### KIA - Kartu Identitas Anak

#### POST /api/pengajuan/kia

Buat pengajuan KIA baru.  
**Memerlukan token. Content-Type: multipart/form-data**

**Data Spesifik KIA (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `tempat_lahir` | string | Ya | Tempat lahir anak |
| `tanggal_lahir` | date | Ya | Tanggal lahir anak (format `Y-m-d`) |
| `jenis_kelamin` | string | Ya | `L` atau `P` |
| `nama_kepala_keluarga` | string | Ya | |
| `agama` | string | Ya | |
| `kewarganegaraan` | string | Ya | Contoh: `WNI` |
| `file_akta_kelahiran` | file | Ya | jpg/png/pdf |
| `file_kk` | file | Ya | jpg/png/pdf |
| `file_surat_nikah` | file | Ya | jpg/png/pdf |
| `file_foto_anak` | file | Ya | jpg/png |

**Response 201**
```json
{
  "message": "Pengajuan KIA berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/kia/{id}

Revisi pengajuan KIA yang ditolak. Kirim hanya field yang ingin diperbarui.  
**Memerlukan token.**

---

### 3 in 1 - Akta Kelahiran + KK + KIA

Layanan gabungan: mengurus Akta Kelahiran, pembaruan KK, dan KIA sekaligus dalam satu pengajuan.

#### POST /api/pengajuan/3-in-1

**Data Spesifik 3 in 1 (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_anak` | string | Ya | Nama anak yang akan didaftarkan |
| `tanggal_lahir_anak` | date | Ya | Format `Y-m-d` |
| `file_sk_lahir` | file | Ya | Surat keterangan lahir |
| `file_kk` | file | Ya | Kartu Keluarga |
| `file_ktp_ortu` | file | Ya | KTP orang tua |
| `file_surat_nikah` | file | Ya | Buku nikah |
| `file_foto_anak` | file | Ya | Foto anak |

**Response 201**
```json
{
  "message": "Pengajuan 3 in 1 berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/3-in-1/{id}

Revisi pengajuan 3 in 1. Kirim hanya field yang ingin diperbarui.  
**Memerlukan token.**

---

### KK Penambahan

Pengajuan untuk menambah anggota baru ke dalam Kartu Keluarga.

#### POST /api/pengajuan/kk-penambahan

**Data Spesifik KK Penambahan (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_kepala_keluarga` | string | Ya | |
| `nomor_kk` | string | Ya | Nomor KK yang akan ditambah anggotanya |
| `nama_ketua_rt` | string | Ya | |
| `nama_ketua_rw` | string | Ya | |
| `nama_lengkap_tambahan` | string | Ya | Nama anggota yang ditambahkan |
| `jenis_kelamin_tambahan` | string | Ya | `L` atau `P` |
| `tempat_lahir_tambahan` | string | Ya | |
| `tanggal_lahir_tambahan` | date | Ya | Format `Y-m-d` |
| `status_hubungan` | string | Ya | Contoh: `Anak`, `Istri`, `Suami` |
| `kelainan_fisik_mental` | string | Ya | Contoh: `Tidak Ada` |
| `penyandang_cacat` | string | Ya | Contoh: `Tidak` |
| `agama` | string | Ya | |
| `nama_ibu_kandung` | string | Ya | |
| `nik_ibu` | string | Ya | 16 digit NIK ibu |
| `nama_ayah_kandung` | string | Ya | |
| `nik_ayah` | string | Ya | 16 digit NIK ayah |
| `file_kk_asli` | file | Ya | Foto KK yang berlaku |
| `file_sk_lahir_akta` | file | Ya | Surat keterangan lahir / akta |
| `file_ktp_suami_istri` | file | Ya | KTP suami/istri |
| `file_surat_nikah` | file | Ya | Buku nikah |

**Response 201**
```json
{
  "message": "Pengajuan KK Penambahan berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/kk-penambahan/{id}

Revisi pengajuan KK Penambahan.  
**Memerlukan token.**

---

### KK Pengurangan

Pengajuan untuk menghapus anggota dari Kartu Keluarga (karena pindah atau meninggal).

#### POST /api/pengajuan/kk-pengurangan

**Data Spesifik KK Pengurangan (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `alasan_pengurangan` | string | Ya | Contoh: `Pindah domisili`, `Meninggal dunia` |
| `nama_lengkap_anggota` | string | Ya | Nama anggota yang dikurangi |
| `alamat_lengkap_anggota` | string | Ya | Alamat anggota tersebut |
| `nik_anggota` | string | Ya | NIK anggota yang dikurangi |
| `file_kk_asli` | file | Ya | Foto KK saat ini |
| `file_ktp_asli` | file | Ya | KTP anggota yang dikurangi |
| `file_sk_pindah_mati` | file | Ya | Surat keterangan pindah atau surat kematian |

**Response 201**
```json
{
  "message": "Pengajuan KK Pengurangan berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/kk-pengurangan/{id}

Revisi pengajuan KK Pengurangan.  
**Memerlukan token.**

---

### KK Perbaikan

Pengajuan untuk memperbaiki data yang salah di Kartu Keluarga.

#### POST /api/pengajuan/kk-perbaikan

**Data Spesifik KK Perbaikan (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `jenis_perbaikan_id` | integer | Ya | ID dari master jenis perbaikan (ambil dari `/api/desas` nantinya atau hardcode dari master data) |
| `nama_kepala_keluarga` | string | Ya | |
| `nomor_kk` | string | Ya | |
| `nama_anggota_yang_diperbaiki` | string | Ya | Nama anggota yang datanya akan diperbaiki |
| `data_perbaikan` | object | Ya | Key-value data lama dan data baru. Contoh: `{"nama_lama": "Budi", "nama_baru": "Budi Santoso"}` |
| `file_pendukung[]` | file[] | Ya | Satu atau lebih file pendukung (array). Kirim sebagai `file_pendukung[0]`, `file_pendukung[1]`, dst. |

> **Catatan `data_perbaikan`:** Kirim sebagai JSON string di multipart/form-data. Contoh value: `{"nama_lama":"Budi","nama_baru":"Budi Santoso"}`.

**Response 201**
```json
{
  "message": "Pengajuan KK Perbaikan berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/kk-perbaikan/{id}

Revisi pengajuan KK Perbaikan. Jika `file_pendukung` dikirim, semua file lama akan digantikan.  
**Memerlukan token.**

---

### Akta Kelahiran

Pengajuan untuk mendapatkan Akta Kelahiran anak (tanpa sekaligus mengurus KK dan KIA).

#### POST /api/pengajuan/akta-lahir

**Data Spesifik Akta Kelahiran (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_anak` | string | Ya | Nama anak |
| `tanggal_lahir_anak` | date | Ya | Format `Y-m-d` |
| `file_sk_lahir` | file | Ya | Surat keterangan lahir dari RS/bidan |
| `file_kk` | file | Ya | Kartu Keluarga |
| `file_ktp_ayah` | file | Ya | KTP ayah |
| `file_ktp_ibu` | file | Ya | KTP ibu |
| `file_surat_nikah` | file | Ya | Buku nikah |

**Response 201**
```json
{
  "message": "Pengajuan Akta Kelahiran berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/akta-lahir/{id}

Revisi pengajuan Akta Kelahiran.  
**Memerlukan token.**

---

### Akta Kematian

Pengajuan untuk mendapatkan Akta Kematian anggota keluarga.

#### POST /api/pengajuan/akta-kematian

**Data Spesifik Akta Kematian (selain data diri pemohon)**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_lengkap_anggota` | string | Ya | Nama anggota yang meninggal |
| `alamat_lengkap_anggota` | string | Ya | Alamat anggota yang meninggal |
| `nik_anggota` | string | Ya | NIK anggota yang meninggal |
| `file_kk_asli` | file | Ya | Kartu Keluarga |
| `file_ktp_asli` | file | Ya | KTP anggota yang meninggal |
| `file_sk_kematian` | file | Ya | Surat keterangan kematian dari RS/kelurahan |

**Response 201**
```json
{
  "message": "Pengajuan Akta Kematian berhasil dibuat.",
  "data": { ... }
}
```

---

#### POST /api/pengajuan/akta-kematian/{id}

Revisi pengajuan Akta Kematian.  
**Memerlukan token.**

---

## Status Pengajuan

| Status | Label | Keterangan |
|--------|-------|------------|
| `berkas_diterima` | Berkas Diterima | Pengajuan masuk, menunggu verifikasi admin desa |
| `ditolak_desa` | Ditolak Desa | Ditolak oleh admin desa, warga perlu merevisi |
| `diverifikasi_desa` | Diverifikasi Desa | Lulus verifikasi desa, diteruskan ke kecamatan |
| `ditolak_kecamatan` | Ditolak Kecamatan | Ditolak oleh admin kecamatan, warga perlu merevisi |
| `diproses_kecamatan` | Diproses Kecamatan | Sedang diproses di kecamatan |
| `selesai` | Selesai | Dokumen sudah selesai, bisa didownload via `lokasi_dokumen` |

---

## Kode Error

| Kode | Keterangan |
|------|------------|
| `200` | OK — Request berhasil |
| `201` | Created — Data berhasil dibuat |
| `401` | Unauthenticated — Token tidak valid, expired, atau tidak ada |
| `403` | Forbidden — Tidak punya akses ke resource ini |
| `404` | Not Found — Resource tidak ditemukan |
| `422` | Unprocessable — Validasi gagal, cek field `errors` |
| `500` | Server Error — Hubungi developer backend |

---

## Catatan untuk Developer Mobile

1. **Simpan token** dari response login/register di secure storage (misal: Flutter Secure Storage / Keychain).
2. **Pre-fill form pengajuan** dengan data dari `GET /api/profile` untuk UX yang lebih baik.
3. **Semua upload file** menggunakan `multipart/form-data`, bukan JSON.
4. **Polling status** bisa dilakukan via `GET /api/pengajuan/{id}/status` yang lebih ringan daripada fetch detail lengkap.
5. **Pagination** di list pengajuan: gunakan `meta.current_page` dan `meta.last_page` untuk infinite scroll.
6. **Jika token expired** (response 401), redirect ke halaman login dan minta user login ulang.
7. **KK Perbaikan** — `data_perbaikan` harus di-encode sebagai JSON string saat dikirim via multipart.
8. **KK Perbaikan** — `file_pendukung` dikirim sebagai array: `file_pendukung[0]`, `file_pendukung[1]`, dst.
