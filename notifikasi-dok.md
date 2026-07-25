# Sistem Notifikasi SIRAMA

Dokumentasi teknis sistem notifikasi real-time berbasis Laravel Notification + Reverb WebSocket.

---

## Daftar Isi

- [Arsitektur](#arsitektur)
- [Model Data](#model-data)
- [Jenis Notifikasi](#jenis-notifikasi)
- [Alur Notifikasi](#alur-notifikasi)
- [Komponen UI](#komponen-ui)
- [API Endpoints](#api-endpoints)
- [WebSocket / Real-time](#websocket--real-time)
- [Konfigurasi](#konfigurasi)

---

## Arsitektur

Setiap notifikasi dikirim melalui **dua channel paralel**:

| Channel | Fungsi |
|---------|--------|
| `database` | Menyimpan ke tabel `notifications` (riwayat, badge count) |
| `broadcast` | Real-time via Laravel Reverb (WebSocket) |

Struktur komponen:

```
[Trigger Event]
    ↓
[PHP Notification Class]  →  database channel  →  tabel notifications
    ↓
    broadcast channel  →  Laravel Reverb  →  Echo (frontend)
                                                ↓
                                    notification-bell.js (Alpine.js)
                                                ↓
                                    Update badge + dropdown + toast
```

---

## Model Data

### Database Schema

Tabel `notifications`:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | UUID | Primary key |
| `type` | string | Class notifikasi, e.g. `App\Notifications\StatusBerubahNotification` |
| `notifiable_id` | integer | ID user penerima |
| `notifiable_type` | string | `App\Models\User` |
| `data` | JSON | Payload notifikasi |
| `read_at` | timestamp|null | Waktu dibaca |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

Index: `(notifiable_type, notifiable_id, read_at)`

### Struktur Payload `data`

```json
{
  "title":        "string",
  "message":      "string",
  "pengajuan_id": "int|null",
  "jenis_layanan":"string",
  "status":       "string",
  "action":       "string",
  "icon":         "string",
  "color":        "string"
}
```

### Field `action`

| Nilai | Kegunaan |
|-------|----------|
| `view_detail` | Buka halaman detail pengajuan |
| `verify` | Buka halaman verifikasi (admin desa) |
| `process` | Buka halaman proses (admin kecamatan) |
| `resubmit` | Buka halaman pengajuan ulang (ditolak) |
| `download` | Buka halaman unduh dokumen |

### Field `color`

| Nilai | Makna |
|-------|-------|
| `success` | Berhasil / selesai |
| `info` | Informasi |
| `warning` | Peringatan |
| `danger` | Ditolak / error |

---

## Jenis Notifikasi

### 1. PengajuanBaruNotification

Dikirim ke **admin desa** saat warga membuat pengajuan baru.

| Field | Nilai Contoh |
|-------|-------------|
| title | "Pengajuan Baru" |
| message | "Pengajuan KIA an. Siti Rahmawati (NIK: 1234567890123456) dari Desa Sukamaju" |
| action | `verify` |
| icon | `file-text` |
| color | `info` |

### 2. PengajuanDiterimaNotification

Dikirim ke **warga** sebagai konfirmasi bahwa pengajuan diterima sistem.

| Field | Nilai Contoh |
|-------|-------------|
| title | "Pengajuan Diterima" |
| message | "Pengajuan KIA Anda telah diterima dan sedang diproses oleh admin desa." |
| action | `view_detail` |
| icon | `check-circle` |
| color | `success` |

### 3. PengajuanSiapDiprosesNotification

Dikirim ke **admin kecamatan** setelah admin desa memverifikasi pengajuan.

| Field | Nilai Contoh |
|-------|-------------|
| title | "Pengajuan Siap Diproses" |
| message | "Pengajuan KIA an. Siti Rahmawati telah diverifikasi oleh Desa Sukamaju dan siap diproses." |
| action | `process` |
| icon | `send` |
| color | `info` |

### 4. StatusBerubahNotification

Dikirim ke **warga** saat status pengajuannya berubah. Bersifat dinamis:

| Status | action | color | icon |
|--------|--------|-------|------|
| `diverifikasi_desa` | `view_detail` | `info` | `info` |
| `ditolak_desa` | `resubmit` | `danger` | `x-circle` |
| `diverifikasi_kecamatan` | `view_detail` | `info` | `info` |
| `ditolak_kecamatan` | `resubmit` | `danger` | `x-circle` |
| `selesai` | `download` | `success` | `check-circle` |

Juga menyertakan field opsional: `catatan`, `status_label`.

---

## Alur Notifikasi

### Alur Pengajuan Baru

```
Warga submit pengajuan
    ↓
BasePengajuanController::store()
    ↓
sendPengajuanCreatedNotifications($pengajuan)
    ↓
├── Notifikasi ke warga: PengajuanDiterimaNotification
│   └── channel: database + broadcast
│
├── Notifikasi ke admin desa: PengajuanBaruNotification
│   └── channel: database + broadcast
│
└── Broadcast event: PengajuanCreated
    └── private channel user warga
```

### Alur Verifikasi Desa

```
Desa verifikasi (approve/tolak)
    ↓
Desa\PengajuanController::verifikasi()
    ↓
├── Update status pengajuan
├── Simpan riwayat status
├── Kirim StatusBerubahNotification ke warga
│
├── Jika approve:
│   └── Kirim PengajuanSiapDiprosesNotification ke admin kecamatan
│
└── Broadcast event: StatusPengajuanUpdated
```

### Alur Proses Kecamatan

```
Kecamatan proses (approve/tolak/selesai)
    ↓
Kecamatan\PengajuanController::proses()
    ↓
├── Update status pengajuan
├── Kirim StatusBerubahNotification ke warga
└── Broadcast event: StatusPengajuanUpdated
```

---

## Komponen UI

### Notification Bell (`x-notification-bell`)

Komponen Blade + Alpine.js yang muncul di navbar (semua halaman terautentikasi).

**Fitur:**
- Ikon lonceng dengan badge jumlah notifikasi belum dibaca
- Dropdown panel (375px) berisi 10 notifikasi terbaru
- Tombol "Tandai semua sudah dibaca"
- Tombol hapus per notifikasi
- Tautan "Lihat semua notifikasi" (menuju halaman khusus - belum diimplementasikan)
- Waktu relatif: "Baru saja", "5 menit lalu", "1 jam lalu", dll (locale Indonesia)

**Interaksi:**
| Aksi | Method | Endpoint |
|------|--------|----------|
| Klik notifikasi | POST | `/api/notifications/{id}/read` + navigasi berdasarkan `action` |
| Tandai semua dibaca | POST | `/api/notifications/read-all` |
| Hapus notifikasi | DELETE | `/api/notifications/{id}` |

**Real-time:**
- Subscribe ke `private-App.Models.User.{userId}` via Laravel Echo
- Event notifikasi masuk langsung muncul di daftar tanpa reload
- Polling fallback setiap 30 detik (`fetchUnreadCount`) jika WebSocket terputus

**Toast Notification:**
Komponen memdispatch event DOM `notification-toast` dengan payload `{ title, message }` untuk ditangkap oleh komponen toast (belum diimplementasikan).

### Navigasi Berdasarkan Action

Saat notifikasi diklik, sistem menavigasi ke halaman berdasarkan nilai `action`:

| action | Route |
|--------|-------|
| `verify` | `/desa/pengajuan/{pengajuan_id}` |
| `process` | `/kecamatan/pengajuan/{pengajuan_id}` |
| lainnya (dengan pengajuan_id) | `/pengajuan/{pengajuan_id}` |
| tanpa pengajuan_id | tidak melakukan navigasi |

---

## API Endpoints

Semua endpoint membutuhkan autentikasi **Bearer Token** (Laravel Sanctum).

### GET /api/notifications

Ambil daftar notifikasi (terbaru lebih dulu).

**Parameters:**

| Parameter | Tipe | Default | Keterangan |
|-----------|------|---------|------------|
| `per_page` | int | 20 | Jumlah per halaman |
| `page` | int | 1 | Halaman |

**Response `200`:**

```json
{
  "data": [
    {
      "id": "uuid",
      "type": "App\\Notifications\\StatusBerubahNotification",
      "data": {
        "title": "Pengajuan Diverifikasi",
        "message": "Pengajuan KIA Anda telah diverifikasi oleh admin desa.",
        "pengajuan_id": 1,
        "action": "view_detail",
        "icon": "info",
        "color": "info",
        "jenis_layanan": "kia",
        "status": "diverifikasi_desa"
      },
      "read_at": null,
      "created_at": "2026-07-25T10:00:00.000000Z",
      "time_ago": "5 menit lalu"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 20,
    "total": 1,
    "unread_count": 1
  }
}
```

### GET /api/notifications/unread-count

Ambil jumlah notifikasi belum dibaca.

**Response `200`:**

```json
{
  "unread_count": 3
}
```

### POST /api/notifications/{id}/read

Tandai satu notifikasi sebagai sudah dibaca.

**Response `200`:**

```json
{
  "message": "Notifikasi telah ditandai sudah dibaca"
}
```

### POST /api/notifications/read-all

Tandai semua notifikasi user sebagai sudah dibaca.

**Response `200`:**

```json
{
  "message": "Semua notifikasi telah ditandai sudah dibaca"
}
```

### DELETE /api/notifications/{id}

Hapus notifikasi.

**Response `200`:**

```json
{
  "message": "Notifikasi berhasil dihapus"
}
```

**Response `404`:**

```json
{
  "message": "Notifikasi tidak ditemukan"
}
```

---

## WebSocket / Real-time

### Teknologi

- **Server:** Laravel Reverb (WebSocket server native Laravel)
- **Client:** Laravel Echo + Pusher.js (berjalan di browser)

### Autorisasi Channel

File: `routes/channels.php`

```php
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

Setiap user hanya bisa mendengarkan channel pribadinya sendiri.

### Event Broadcast

Selain notifikasi, dua event berikut juga di-broadcast:

| Event | Channel | Event Name | Payload |
|-------|---------|------------|---------|
| `PengajuanCreated` | `App.Models.User.{warga_id}` | `pengajuan.created` | pengajuan_id, jenis_layanan, status, status_label, created_at |
| `StatusPengajuanUpdated` | `App.Models.User.{warga_id}` | `status.updated` | pengajuan_id, status, status_label, created_at, catatan |

### Frontend Echo Setup

File: `resources/js/app.js`

```js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

### Polling Fallback

Jika koneksi WebSocket terputus, sistem akan melakukan polling setiap 30 detik ke endpoint `/api/notifications/unread-count` sebagai fallback.

---

## Konfigurasi

### Environment Variables (`.env`)

```ini
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database

REVERB_APP_ID=950126
REVERB_APP_KEY=***
REVERB_APP_SECRET=***
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Queue

Semua notifikasi menggunakan queue (trait `Queueable`) dengan driver `database`.

Jalankan worker:

```bash
php artisan queue:work
```

### Dependencies

**Backend (Composer):**
- `laravel/reverb` ^1.10

**Frontend (npm):**
- `laravel-echo` ^2.4.0
- `pusher-js` ^8.6.0

---

## File Reference

| File | Lokasi | Fungsi |
|------|--------|--------|
| Notification classes | `app/Notifications/*.php` | Definisi notifikasi (database + broadcast) |
| NotificationController | `app/Http/Controllers/Api/NotificationController.php` | REST API CRUD |
| Migration | `database/migrations/*_create_notifications_table.php` | Skema tabel |
| Component JS | `resources/js/components/notification-bell.js` | Alpine.js logic |
| Component Blade | `resources/views/components/notification-bell.blade.php` | UI dropdown |
| Navbar | `resources/views/layouts/partials/navbar.blade.php` | Tempat include komponen |
| Channel auth | `routes/channels.php` | Authorisasi broadcast channel |
| Broadcast config | `config/broadcasting.php` | Konfigurasi Reverb |
| Reverb config | `config/reverb.php` | Konfigurasi detail Reverb |

---

## Catatan Pengembangan

- Halaman "Lihat semua notifikasi" (`route('notifications.index')`) belum diimplementasikan
- Komponen toast untuk notifikasi real-time (`notification-toast` event) belum diimplementasikan
- Channel email/SMS/WhatsApp belum tersedia (hanya database + broadcast)
- Pengaturan preferensi notifikasi per user belum tersedia
- Push notification mobile (FCM/APNS) belum tersedia
