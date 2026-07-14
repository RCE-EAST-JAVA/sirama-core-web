# SIRAMA — Sistem Informasi Administrasi Masyarakat

Aplikasi web untuk pengelolaan pengajuan layanan administrasi kependudukan di Kecamatan Sukosari. Dibangun dengan Laravel 11, Tailwind CSS, dan Alpine.js.

---

## Fitur

- Pengajuan layanan kependudukan secara online (KIA, KK, Akta Kelahiran, Akta Kematian)
- Manajemen alur verifikasi: Admin Desa → Admin Kecamatan
- Dashboard statistik per role
- OCR dokumen otomatis
- REST API untuk aplikasi mobile (Laravel Sanctum)
- Manajemen user oleh Admin Aplikasi

## Role

| Role | Akses |
|------|-------|
| `warga` | Submit & pantau pengajuan via mobile app |
| `admin_desa` | Verifikasi pengajuan di level desa |
| `admin_kecamatan` | Proses & selesaikan pengajuan di level kecamatan |
| `admin_aplikasi` | Manajemen user dan riwayat semua pengajuan |

---

## Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL

### Langkah Setup

```bash
# Clone repository
git clone <repo-url>
cd sirama-core-web

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=db_sirama
# DB_USERNAME=root
# DB_PASSWORD=

# Jalankan migration dan seeder
php artisan migrate
php artisan db:seed

# Link storage
php artisan storage:link

# Build assets
npm run dev
```

### Akun Default (dari Seeder)

| NIK | Role | Password |
|-----|------|----------|
| `0000000000000001` | Admin Aplikasi | `password` |
| `0000000000000002` | Admin Kecamatan | `password` |
| `0000000000000003` | Admin Desa Sukosari Lor | `password` |
| `0000000000000004` | Admin Desa Nogosari | `password` |
| `0000000000000005` | Admin Desa Kerang | `password` |
| `3277010101900001` | Warga | `password` |

---

## Struktur Route

| Prefix | Middleware | Keterangan |
|--------|------------|------------|
| `/admin` | `auth`, `role:admin_aplikasi` | Dashboard & manajemen user |
| `/desa` | `auth`, `role:admin_desa` | Verifikasi pengajuan |
| `/kecamatan` | `auth`, `role:admin_kecamatan` | Proses pengajuan |
| `/api` | Sanctum (token) | REST API untuk mobile |

---

## API Mobile

API tersedia untuk pengembangan aplikasi mobile. Autentikasi menggunakan Laravel Sanctum (Bearer Token).

**Base URL:** `http://{domain}/api`

**Endpoint tersedia:**
- `POST /api/auth/register` — Registrasi warga
- `POST /api/auth/login` — Login
- `POST /api/auth/logout` — Logout
- `GET  /api/desas` — Daftar desa (publik)
- `GET  /api/profile` — Profil user
- `POST /api/profile` — Update profil
- `GET  /api/pengajuan` — List pengajuan
- `GET  /api/pengajuan/{id}` — Detail pengajuan
- `GET  /api/pengajuan/{id}/status` — Cek status
- `POST /api/pengajuan/kia` — Pengajuan KIA
- `POST /api/pengajuan/3-in-1` — Pengajuan 3 in 1
- `POST /api/pengajuan/kk-penambahan` — KK Penambahan
- `POST /api/pengajuan/kk-pengurangan` — KK Pengurangan
- `POST /api/pengajuan/kk-perbaikan` — KK Perbaikan
- `POST /api/pengajuan/akta-lahir` — Akta Kelahiran
- `POST /api/pengajuan/akta-kematian` — Akta Kematian

Dokumentasi lengkap ada di [`API_DOCUMENTATION.md`](./API_DOCUMENTATION.md).

---

## Tech Stack

- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Database:** MySQL
- **Auth Web:** Laravel Breeze (session)
- **Auth API:** Laravel Sanctum (token)
- **Queue/Jobs:** Laravel Queue

---

## Lisensi

Internal project — Kecamatan Sukosari.
