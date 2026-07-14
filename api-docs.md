# SIRAMA API Documentation

Base URL Production: `https://sirama.tunggulmajid.my.id/api`  
Base URL Local: `http://127.0.0.1:8000/api`

Autentikasi menggunakan **Laravel Sanctum Bearer Token**.  
Sertakan header `Authorization: Bearer {token}` pada semua endpoint yang membutuhkan autentikasi.

---

## Daftar Isi

- [Auth](#auth)
- [Desa](#desa)
- [Profile](#profile)
- [Pengajuan](#pengajuan)
- [Pengajuan - KIA](#pengajuan---kia)
- [Pengajuan - 3 in 1](#pengajuan---3-in-1)
- [Pengajuan - KK Penambahan](#pengajuan---kk-penambahan)
- [Pengajuan - KK Pengurangan](#pengajuan---kk-pengurangan)
- [Pengajuan - KK Perbaikan](#pengajuan---kk-perbaikan)
- [Pengajuan - Akta Lahir](#pengajuan---akta-lahir)
- [Pengajuan - Akta Kematian](#pengajuan---akta-kematian)
- [Status Pengajuan](#status-pengajuan)

---

## Auth

### POST /auth/register

Registrasi warga baru. Field profil bersifat opsional dan bisa dilengkapi nanti via update profile.

**Request** `application/json`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nik` | string (16 digit) | Ya | NIK sebagai username |
| `name` | string | Ya | Nama lengkap |
| `no_whatsapp` | string (maks 20) | Ya | Nomor WhatsApp |
| `password` | string (min 8) | Ya | Password |
| `password_confirmation` | string | Ya | Konfirmasi password |
| `tanggal_lahir` | date (YYYY-MM-DD) | Tidak | - |
| `jenis_kelamin` | enum: `L`, `P` | Tidak | - |
| `pekerjaan` | string | Tidak | - |
| `alamat` | string | Tidak | - |
| `desa` | string | Tidak | Harus sesuai nama desa yang terdaftar |
| `rt` | string | Tidak | - |
| `rw` | string | Tidak | - |

```json
{
  "nik": "3277010101900001",
  "name": "Budi Santoso",
  "no_whatsapp": "08123456789",
  "password": "password",
  "password_confirmation": "password",
  "tanggal_lahir": "1990-01-01",
  "jenis_kelamin": "L",
  "alamat": "Jl. Merdeka No. 1",
  "desa": "Desa Sukosari Lor",
  "rt": "001",
  "rw": "002"
}
```

**Response 201 — Registrasi berhasil**

```json
{
  "message": "Registrasi berhasil.",
  "token": "1|abc123xyz",
  "user": {
    "id": 1,
    "nik": "3277010101900001",
    "name": "Budi Santoso",
    "no_whatsapp": "08123456789",
    "role": "warga",
    "desa": "Desa Sukosari Lor"
  }
}
```

**Response 422 — Validasi gagal**

```json
{
  "message": "The nik field is required.",
  "errors": {
    "nik": ["NIK wajib diisi."],
    "password": ["Password wajib diisi."]
  }
}
```

---

### POST /auth/login

Login menggunakan NIK dan password. Hanya akun dengan role `warga` yang dapat login via API mobile. Token lama akan dihapus dan diganti token baru.

**Request** `application/json`

| Field | Tipe | Wajib |
|---|---|---|
| `nik` | string (16 digit) | Ya |
| `password` | string | Ya |

```json
{
  "nik": "3277010101900001",
  "password": "password"
}
```

**Response 200 — Login berhasil**

```json
{
  "message": "Login berhasil.",
  "token": "1|abc123xyz",
  "user": {
    "id": 1,
    "nik": "3277010101900001",
    "name": "Budi Santoso",
    "no_whatsapp": "08123456789",
    "role": "warga",
    "desa": null
  }
}
```

**Response 403** — Akun bukan warga (admin tidak bisa login via mobile)  
**Response 422** — NIK atau password salah

---

### POST /auth/logout

🔒 Membutuhkan autentikasi.

Menghapus token aktif. Setelah logout, token tidak bisa digunakan lagi.

**Request** — tidak ada body

**Response 200**

```json
{
  "message": "Logout berhasil."
}
```

**Response 401** — Token tidak valid atau tidak ada

---

## Desa

### GET /desas

Endpoint publik — tidak perlu token. Digunakan untuk mengisi dropdown pilihan desa di frontend.

**Response 200**

```json
{
  "data": [
    {
      "id": 1,
      "nama": "Desa Sukosari Lor",
      "kecamatan": "Sukosari"
    }
  ]
}
```

---

## Profile

### GET /profile

🔒 Membutuhkan autentikasi.

Mengambil data profil user yang sedang login.

**Response 200**

```json
{
  "data": {
    "id": 1,
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
    "foto_profil": null,
    "role": "warga"
  }
}
```

**Response 401** — Unauthenticated

---

### POST /profile

🔒 Membutuhkan autentikasi.

Update profil user yang sedang login. Semua field opsional — kirim hanya field yang ingin diubah.

**Request** `multipart/form-data`

| Field | Tipe | Keterangan |
|---|---|---|
| `name` | string | - |
| `nik` | string | - |
| `no_whatsapp` | string | - |
| `tanggal_lahir` | date | - |
| `jenis_kelamin` | enum: `L`, `P` | - |
| `pekerjaan` | string | - |
| `alamat` | string | - |
| `desa` | string | - |
| `rt` | string | - |
| `rw` | string | - |
| `foto_profil` | file (jpg/png, maks 2MB) | - |
| `password` | string | - |
| `password_confirmation` | string | Wajib jika `password` diisi |

**Response 200** — Profil berhasil diupdate  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

## Pengajuan

### GET /pengajuan

🔒 Membutuhkan autentikasi.

Daftar semua pengajuan milik warga yang sedang login, diurutkan terbaru. Mendukung filter berdasarkan status.

**Query Parameters**

| Parameter | Tipe | Keterangan |
|---|---|---|
| `status` | enum | `berkas_diterima`, `ditolak_desa`, `diverifikasi_desa`, `ditolak_kecamatan`, `diverifikasi_kecamatan`, `selesai` |

**Response 200**

```json
{
  "data": [
    {
      "id": 1,
      "jenis_layanan": "kia",
      "label_layanan": "KIA (Kartu Identitas Anak)",
      "status": "berkas_diterima",
      "label_status": "Berkas Diterima",
      "no_whatsapp": "08123456789",
      "lokasi_dokumen": null,
      "created_at": "2026-01-01T08:00:00+07:00",
      "updated_at": "2026-01-01T08:00:00+07:00",
      "user": {
        "id": 1,
        "nik": "3277010101900001",
        "name": "Budi Santoso",
        "no_whatsapp": "08123456789",
        "role": "warga",
        "desa": null
      },
      "riwayat_statuses": [
        {
          "status": "berkas_diterima",
          "catatan": "Pengajuan diterima.",
          "waktu": "2026-01-01T08:00:00+07:00"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "total": 25
  }
}
```

**Response 401** — Unauthenticated

---

### GET /pengajuan/stats

🔒 Membutuhkan autentikasi.

Statistik jumlah pengajuan milik warga yang sedang login, dikelompokkan per status beserta labelnya.

**Response 200**

```json
{
  "data": {
    "total": 7,
    "statuses": [
      { "status": "berkas_diterima",        "label": "Berkas Diterima",        "jumlah": 2 },
      { "status": "diverifikasi_desa",      "label": "Diverifikasi Desa",      "jumlah": 1 },
      { "status": "ditolak_desa",           "label": "Ditolak Desa",           "jumlah": 0 },
      { "status": "diverifikasi_kecamatan", "label": "Diverifikasi Kecamatan", "jumlah": 1 },
      { "status": "ditolak_kecamatan",      "label": "Ditolak Kecamatan",      "jumlah": 1 },
      { "status": "selesai",                "label": "Selesai",                "jumlah": 2 }
    ]
  }
}
```

**Response 401** — Unauthenticated

---

### GET /pengajuan/{id}

🔒 Membutuhkan autentikasi.

Detail satu pengajuan milik warga yang sedang login.

**Path Parameter:** `id` (integer)

**Response 200**

```json
{
  "id": 1,
  "jenis_layanan": "kia",
  "label_layanan": "KIA (Kartu Identitas Anak)",
  "status": "berkas_diterima",
  "label_status": "Berkas Diterima",
  "no_whatsapp": "08123456789",
  "lokasi_dokumen": null,
  "created_at": "2026-01-01T08:00:00+07:00",
  "updated_at": "2026-01-01T08:00:00+07:00",
  "user": { "id": 1, "nik": "3277010101900001", "name": "Budi Santoso", "no_whatsapp": "08123456789", "role": "warga", "desa": null },
  "riwayat_statuses": [
    { "status": "berkas_diterima", "catatan": "Pengajuan diterima.", "waktu": "2026-01-01T08:00:00+07:00" }
  ]
}
```

**Response 403** — Forbidden (bukan milik user ini)  
**Response 404** — Not Found

---

### GET /pengajuan/{id}/status

🔒 Membutuhkan autentikasi.

Cek status terkini pengajuan. Cocok untuk polling dari aplikasi mobile.

**Path Parameter:** `id` (integer)

**Response 200**

```json
{
  "status": "diverifikasi_desa",
  "label_status": "Diverifikasi Desa",
  "lokasi_dokumen": null
}
```

**Response 403** — Forbidden  
**Response 404** — Not Found

---

## Status Pengajuan

| Status | Label | Keterangan |
|---|---|---|
| `berkas_diterima` | Berkas Diterima | Pengajuan baru masuk |
| `diverifikasi_desa` | Diverifikasi Desa | Diteruskan ke kecamatan oleh desa |
| `ditolak_desa` | Ditolak Desa | Ditolak oleh admin desa |
| `diverifikasi_kecamatan` | Diverifikasi Kecamatan | Sedang diproses di kecamatan |
| `ditolak_kecamatan` | Ditolak Kecamatan | Ditolak oleh admin kecamatan |
| `selesai` | Selesai | Dokumen sudah selesai diproses |

Alur normal: `berkas_diterima` → `diverifikasi_desa` → `diverifikasi_kecamatan` → `selesai`

---

## Pengajuan - KIA

### POST /pengajuan/kia

🔒 Membutuhkan autentikasi.

Buat pengajuan Kartu Identitas Anak baru.

**Request** `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nama_lengkap` | string | Ya | Nama lengkap anak |
| `tempat_lahir` | string | Ya | - |
| `tanggal_lahir` | date | Ya | - |
| `jenis_kelamin` | enum: `L`, `P` | Ya | - |
| `nama_kepala_keluarga` | string | Ya | - |
| `agama` | string | Ya | - |
| `kewarganegaraan` | string | Ya | Contoh: `WNI` |
| `file_akta_kelahiran` | file (jpg/png/pdf, maks 5MB) | Ya | - |
| `file_kk` | file (jpg/png/pdf, maks 5MB) | Ya | - |
| `file_surat_nikah` | file (jpg/png/pdf, maks 5MB) | Ya | - |
| `file_foto_anak` | file (jpg/png/pdf, maks 5MB) | Ya | - |

**Response 201**

```json
{
  "message": "Pengajuan KIA berhasil dibuat.",
  "data": { "id": 1, "jenis_layanan": "kia", "status": "berkas_diterima", ... }
}
```

**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/kia/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan KIA. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Pengajuan KIA berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - 3 in 1

Layanan 3 in 1: Akta Kelahiran + KK + KIA dalam satu pengajuan.

### POST /pengajuan/3-in-1

🔒 Membutuhkan autentikasi.

**Request** `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `nama_anak` | string | Ya |
| `tanggal_lahir_anak` | date | Ya |
| `file_sk_lahir` | file | Ya |
| `file_kk` | file | Ya |
| `file_ktp_ortu` | file | Ya |
| `file_surat_nikah` | file | Ya |
| `file_foto_anak` | file | Ya |

**Response 201**

```json
{
  "message": "Pengajuan 3 in 1 berhasil dibuat.",
  "data": { "id": 2, "jenis_layanan": "3_in_1", "status": "berkas_diterima", ... }
}
```

**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/3-in-1/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan 3 in 1. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - KK Penambahan

### POST /pengajuan/kk-penambahan

🔒 Membutuhkan autentikasi.

Buat pengajuan penambahan anggota KK.

**Request** `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `nama_kepala_keluarga` | string | Ya |
| `nomor_kk` | string | Ya |
| `nama_ketua_rt` | string | Ya |
| `nama_ketua_rw` | string | Ya |
| `nama_lengkap_tambahan` | string | Ya |
| `jenis_kelamin_tambahan` | enum: `L`, `P` | Ya |
| `tempat_lahir_tambahan` | string | Ya |
| `tanggal_lahir_tambahan` | date | Ya |
| `status_hubungan` | string | Ya |
| `kelainan_fisik_mental` | string | Ya |
| `penyandang_cacat` | string | Ya |
| `agama` | string | Ya |
| `nama_ibu_kandung` | string | Ya |
| `nik_ibu` | string | Ya |
| `nama_ayah_kandung` | string | Ya |
| `nik_ayah` | string | Ya |
| `file_kk_asli` | file | Ya |
| `file_sk_lahir_akta` | file | Ya |
| `file_ktp_suami_istri` | file | Ya |
| `file_surat_nikah` | file | Ya |

**Response 201** — Berhasil dibuat  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/kk-penambahan/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan KK Penambahan. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - KK Pengurangan

### POST /pengajuan/kk-pengurangan

🔒 Membutuhkan autentikasi.

Buat pengajuan pengurangan anggota KK.

**Request** `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `alasan_pengurangan` | string | Ya |
| `nama_lengkap_anggota` | string | Ya |
| `alamat_lengkap_anggota` | string | Ya |
| `nik_anggota` | string | Ya |
| `file_kk_asli` | file | Ya |
| `file_ktp_asli` | file | Ya |
| `file_sk_pindah_mati` | file | Ya |

**Response 201** — Berhasil dibuat  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/kk-pengurangan/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan KK Pengurangan. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - KK Perbaikan

### POST /pengajuan/kk-perbaikan

🔒 Membutuhkan autentikasi.

Buat pengajuan perbaikan data KK. Bisa upload lebih dari satu file pendukung.

**Request** `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `jenis_perbaikan_id` | integer | Ya | ID jenis perbaikan |
| `nama_kepala_keluarga` | string | Ya | - |
| `nomor_kk` | string | Ya | - |
| `nama_anggota_yang_diperbaiki` | string | Ya | - |
| `data_perbaikan` | object/JSON | Ya | Key-value data lama dan baru, contoh: `{"nama_lama":"Budi","nama_baru":"Budi Santoso"}` |
| `file_pendukung[]` | file[] | Ya | Satu atau lebih file pendukung |

**Response 201** — Berhasil dibuat  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/kk-perbaikan/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan KK Perbaikan. Jika `file_pendukung[]` dikirim, semua file lama akan digantikan.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - Akta Lahir

### POST /pengajuan/akta-lahir

🔒 Membutuhkan autentikasi.

Buat pengajuan Akta Kelahiran.

**Request** `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nama_anak` | string | Ya | - |
| `tanggal_lahir_anak` | date | Ya | - |
| `file_sk_lahir` | file | Ya | Surat keterangan lahir dari bidan/klinik/RS/pemerintah desa |
| `file_kk` | file | Ya | - |
| `file_ktp_ayah` | file | Ya | - |
| `file_ktp_ibu` | file | Ya | - |
| `file_surat_nikah` | file | Ya | - |

**Response 201** — Berhasil dibuat  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/akta-lahir/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan Akta Kelahiran. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Pengajuan - Akta Kematian

### POST /pengajuan/akta-kematian

🔒 Membutuhkan autentikasi.

Buat pengajuan Akta Kematian.

**Request** `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nama_lengkap_anggota` | string | Ya | Nama anggota yang meninggal |
| `alamat_lengkap_anggota` | string | Ya | - |
| `nik_anggota` | string | Ya | NIK anggota yang meninggal |
| `file_kk_asli` | file | Ya | - |
| `file_ktp_asli` | file | Ya | - |
| `file_sk_kematian` | file | Ya | Surat keterangan kematian |

**Response 201** — Berhasil dibuat  
**Response 401** — Unauthenticated  
**Response 422** — Validasi gagal

---

### POST /pengajuan/akta-kematian/{id}

🔒 Membutuhkan autentikasi.

Revisi pengajuan Akta Kematian. File yang tidak dikirim tidak akan diubah.

**Path Parameter:** `id` (integer)

**Request** `multipart/form-data` — semua field sama dengan store, semua opsional

**Response 200** — Berhasil diupdate  
**Response 403** — Forbidden  
**Response 404** — Not Found  
**Response 422** — Validasi gagal

---

## Catatan Umum

- Semua endpoint pengajuan (store & update) menggunakan `POST` dengan `multipart/form-data` karena melibatkan file upload.
- Pada endpoint update, field yang tidak dikirim tidak akan diubah.
- File disimpan secara privat (tidak bisa diakses langsung via URL publik).
- Token Sanctum bersifat single-session — login baru akan menghapus token lama.
