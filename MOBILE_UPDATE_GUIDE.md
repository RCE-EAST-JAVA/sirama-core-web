# Panduan Update Frontend Mobile - SIRAMA API v2

**Tanggal:** oktober 2026 
**Versi Backend:** v2.0  
**Target:** Tim Frontend Mobile (Flutter)

---

## 📋 Daftar Perubahan

| No | Perubahan | Prioritas | Dampak Frontend |
|----|-----------|-----------|-----------------|
| 1 | Tambah endpoint **KK Baru** (`POST /api/pengajuan/kk-baru`) | **HIGH** | Perlu tambah halaman form baru |
| 2 | Tambah nilai `jenis_layanan`: **`kk_baru`** | **HIGH** | Update routing & mapping label |
| 3 | Tambah jenis perbaikan KK: **"Perbaikan Pendidikan"** | **MEDIUM** | Update dropdown/picker jenis perbaikan |
| 4 | Perubahan alur status pengajuan | **HIGH** | Ubah logika tombol aksi & stepper |
| 5 | Endpoint filter desa di `/api/desa` | **LOW** | Optional untuk filter dropdown |
| 6 | Endpoint update password (`PUT /api/auth/update-password`) | **HIGH** | Tambah halaman ubah password |

---

## 🆕 1. Endpoint Baru: KK Baru

### Request

```http
POST /api/pengajuan/kk-baru
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

### Body Parameters

| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| `nama_pemohon` | string | ✅ | Nama lengkap pemohon |
| `nik_pemohon` | string | ✅ | 16 digit NIK |
| `nomor_hp` | string | ✅ | Nomor telepon |
| `email` | string | ❌ | Email (opsional) |
| `keterangan` | text | ❌ | Catatan tambahan |
| `file_ktp_pemohon` | file | ✅ | JPG/PNG, max 2MB |
| `file_kk_lama` | file | ✅ | JPG/PNG/PDF, max 2MB |
| `file_surat_pengantar_rt` | file | ✅ | JPG/PNG/PDF, max 2MB |
| `file_surat_pengantar_rw` | file | ✅ | JPG/PNG/PDF, max 2MB |
| `file_akta_nikah` | file | ✅ | JPG/PNG/PDF, max 2MB |
| `file_surat_pindah` | file | ✅ | JPG/PNG/PDF, max 2MB |
| `file_surat_keterangan_pindah` | file | ✅ | JPG/PNG/PDF, max 2MB |

### Response Sukses (201)

```json
{
  "success": true,
  "message": "Pengajuan KK Baru berhasil dibuat",
  "data": {
    "id": 123,
    "nomor_pengajuan": "KK-BARU-2025-001",
    "jenis_layanan": "kk_baru",
    "status_pengajuan": "menunggu_verifikasi_desa",
    "nama_pemohon": "Budi Santoso",
    "nik_pemohon": "3201234567890123",
    "created_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

### Response Error (422)

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "file_ktp_pemohon": ["File KTP pemohon wajib diisi"],
    "nik_pemohon": ["NIK harus 16 digit"]
  }
}
```

### Contoh Implementasi (Flutter + Dio)

```dart
import 'package:dio/dio.dart';

Future<void> submitKkBaru({
  required String namaPemohon,
  required String nikPemohon,
  required String nomorHp,
  String? email,
  String? keterangan,
  required File fileKtpPemohon,
  required File fileKkLama,
  required File fileSuratPengantarRt,
  required File fileSuratPengantarRw,
  required File fileAktaNikah,
  required File fileSuratPindah,
  required File fileSuratKeteranganPindah,
}) async {
  final dio = Dio();
  final token = await getAuthToken(); // Ambil dari storage

  FormData formData = FormData.fromMap({
    'nama_pemohon': namaPemohon,
    'nik_pemohon': nikPemohon,
    'nomor_hp': nomorHp,
    if (email != null) 'email': email,
    if (keterangan != null) 'keterangan': keterangan,
    'file_ktp_pemohon': await MultipartFile.fromFile(
      fileKtpPemohon.path,
      filename: 'ktp_pemohon.jpg',
    ),
    'file_kk_lama': await MultipartFile.fromFile(
      fileKkLama.path,
      filename: 'kk_lama.jpg',
    ),
    'file_surat_pengantar_rt': await MultipartFile.fromFile(
      fileSuratPengantarRt.path,
      filename: 'surat_rt.jpg',
    ),
    'file_surat_pengantar_rw': await MultipartFile.fromFile(
      fileSuratPengantarRw.path,
      filename: 'surat_rw.jpg',
    ),
    'file_akta_nikah': await MultipartFile.fromFile(
      fileAktaNikah.path,
      filename: 'akta_nikah.jpg',
    ),
    'file_surat_pindah': await MultipartFile.fromFile(
      fileSuratPindah.path,
      filename: 'surat_pindah.jpg',
    ),
    'file_surat_keterangan_pindah': await MultipartFile.fromFile(
      fileSuratKeteranganPindah.path,
      filename: 'surat_keterangan_pindah.jpg',
    ),
  });

  try {
    final response = await dio.post(
      'https://api.sirama.com/api/pengajuan/kk-baru',
      data: formData,
      options: Options(
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      ),
    );

    if (response.statusCode == 201) {
      print('Berhasil: ${response.data['message']}');
      // Navigate ke halaman sukses/detail
    }
  } on DioException catch (e) {
    if (e.response?.statusCode == 422) {
      final errors = e.response?.data['errors'];
      print('Validasi error: $errors');
      // Tampilkan error per field
    } else {
      print('Error: ${e.message}');
    }
  }
}
```

### ⚠️ Catatan Penting

- **Total 7 file** yang harus diupload
- Ukuran maksimal per file: **2MB**
- Format yang diterima: **JPG, PNG, PDF**
- **Total request size** bisa mencapai 14MB - pastikan handle timeout dan progress indicator
- Gunakan **multipart/form-data** encoding

---

## 🔄 2. Update Nilai `jenis_layanan`

Sebelumnya ada 8 jenis layanan, sekarang bertambah menjadi **9 jenis**:

```dart
enum JenisLayanan {
  aktaLahir('akta_lahir', 'Akta Kelahiran'),
  aktaKematian('akta_kematian', 'Akta Kematian'),
  kia('kia', 'KIA'),
  kkPenambahan('kk_penambahan', 'KK Penambahan'),
  kkPengurangan('kk_pengurangan', 'KK Pengurangan'),
  kkPerbaikan('kk_perbaikan', 'KK Perbaikan'),
  tiga1('3-1', 'Surat Pindah (3-1)'),
  kkBaru('kk_baru', 'KK Baru'), // ✅ BARU
  ;

  final String value;
  final String label;
  const JenisLayanan(this.value, this.label);
}
```

### Mapping Label untuk UI

```dart
String getJenisLayananLabel(String value) {
  switch (value) {
    case 'akta_lahir':
      return 'Akta Kelahiran';
    case 'akta_kematian':
      return 'Akta Kematian';
    case 'kia':
      return 'Kartu Identitas Anak';
    case 'kk_penambahan':
      return 'KK Penambahan Anggota';
    case 'kk_pengurangan':
      return 'KK Pengurangan Anggota';
    case 'kk_perbaikan':
      return 'KK Perbaikan';
    case '3-1':
      return 'Surat Pindah (Form 3-1)';
    case 'kk_baru':
      return 'Kartu Keluarga Baru'; // ✅ BARU
    default:
      return 'Tidak Diketahui';
  }
}
```

### Update Ikon per Jenis Layanan

```dart
IconData getJenisLayananIcon(String value) {
  switch (value) {
    case 'akta_lahir':
      return Icons.baby_changing_station;
    case 'akta_kematian':
      return Icons.local_hospital;
    case 'kia':
      return Icons.child_care;
    case 'kk_penambahan':
      return Icons.person_add;
    case 'kk_pengurangan':
      return Icons.person_remove;
    case 'kk_perbaikan':
      return Icons.edit_note;
    case '3-1':
      return Icons.moving;
    case 'kk_baru':
      return Icons.family_restroom; // ✅ BARU
    default:
      return Icons.description;
  }
}
```

---



## 📊 4. Perubahan Alur Status Pengajuan

### Alur Lama (Sebelum Update)

```
Warga Submit
    ↓
menunggu_verifikasi_desa
    ↓
[Desa] → verifikasi_desa (approve/reject)
    ↓
menunggu_verifikasi_kecamatan
    ↓
[Kecamatan] → verifikasi_kecamatan (approve/reject)
    ↓
menunggu_verifikasi_admin
    ↓
[Admin] → selesai / ditolak
```

### Alur Baru (Setelah Update)

```
Warga Submit / Admin Desa Buat Langsung
    ↓
┌─────────────────────────────┐
│ menunggu_verifikasi_desa    │ ← Status awal
└─────────────────────────────┘
    ↓
┌─────────────────────────────┐
│ [Desa Review]               │
│ - Approve → lanjut          │
│ - Revisi → perlu_perbaikan  │ ✅ BARU
│ - Tolak → ditolak           │
└─────────────────────────────┘
    ↓
┌─────────────────────────────┐
│ menunggu_verifikasi_kecamatan│
└─────────────────────────────┘
    ↓
┌─────────────────────────────┐
│ [Kecamatan Review]          │
│ - Approve → lanjut          │
│ - Revisi → perlu_perbaikan  │ ✅ BARU
│ - Tolak → ditolak           │
└─────────────────────────────┘
    ↓
┌─────────────────────────────┐
│ menunggu_verifikasi_admin   │
└─────────────────────────────┘
    ↓
┌─────────────────────────────┐
│ [Admin Review]              │
│ - Approve → selesai         │
│ - Revisi → perlu_perbaikan  │ ✅ BARU
│ - Tolak → ditolak           │
└─────────────────────────────┘
```

### Daftar Status Lengkap

| Status | Keterangan | Aksi Warga |
|--------|------------|------------|
| `menunggu_verifikasi_desa` | Menunggu review desa | Tidak ada |
| `perlu_perbaikan` | **[BARU]** Perlu revisi dari warga | **Edit & kirim ulang** |
| `menunggu_verifikasi_kecamatan` | Menunggu review kecamatan | Tidak ada |
| `menunggu_verifikasi_admin` | Menunggu review admin | Tidak ada |
| `selesai` | Pengajuan selesai | Download dokumen |
| `ditolak` | Pengajuan ditolak | Tidak ada (buat baru) |

### Dampak pada Frontend

#### A. Tampilkan Tombol "Perbaiki Pengajuan" untuk Status `perlu_perbaikan`

```dart
if (pengajuan.status == 'perlu_perbaikan') {
  ElevatedButton(
    onPressed: () {
      // Navigate ke form edit dengan data existing
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => EditPengajuanPage(pengajuanId: pengajuan.id),
        ),
      );
    },
    child: Text('Perbaiki Pengajuan'),
  );
}
```

#### B. Update Stepper/Progress Indicator

```dart
Widget buildStatusStepper(String status) {
  int currentStep = 0;
  
  switch (status) {
    case 'menunggu_verifikasi_desa':
      currentStep = 0;
      break;
    case 'perlu_perbaikan':
      currentStep = 0; // Kembali ke awal
      break;
    case 'menunggu_verifikasi_kecamatan':
      currentStep = 1;
      break;
    case 'menunggu_verifikasi_admin':
      currentStep = 2;
      break;
    case 'selesai':
      currentStep = 3;
      break;
    case 'ditolak':
      currentStep = -1; // Khusus ditolak
      break;
  }
  
  return Stepper(
    currentStep: currentStep,
    steps: [
      Step(title: Text('Verifikasi Desa'), content: Container()),
      Step(title: Text('Verifikasi Kecamatan'), content: Container()),
      Step(title: Text('Verifikasi Admin'), content: Container()),
      Step(title: Text('Selesai'), content: Container()),
    ],
  );
}
```

#### C. Tampilkan Catatan Perbaikan

API mengembalikan field `catatan_perbaikan` ketika status `perlu_perbaikan`:

```dart
if (pengajuan.status == 'perlu_perbaikan' && 
    pengajuan.catatanPerbaikan != null) {
  Container(
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.orange.shade50,
      borderRadius: BorderRadius.circular(8),
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(Icons.warning, color: Colors.orange),
            SizedBox(width: 8),
            Text(
              'Perlu Perbaikan',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: Colors.orange.shade900,
              ),
            ),
          ],
        ),
        SizedBox(height: 8),
        Text(
          pengajuan.catatanPerbaikan!,
          style: TextStyle(color: Colors.orange.shade900),
        ),
      ],
    ),
  );
}
```

#### D. Handle Pengajuan yang Dibuat Admin Desa

Pengajuan bisa dibuat oleh:
- **Warga** melalui mobile app
- **Admin Desa** melalui web admin

Ketika admin desa yang membuat, field `dibuat_oleh_admin_desa` bernilai `true`:

```dart
Widget buildPengajuanCard(Pengajuan pengajuan) {
  return Card(
    child: Column(
      children: [
        ListTile(
          title: Text(pengajuan.nomorPengajuan),
          subtitle: Text(getJenisLayananLabel(pengajuan.jenisLayanan)),
        ),
        if (pengajuan.dibuatOlehAdminDesa) // ✅ Indikator baru
          Container(
            padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              children: [
                Icon(Icons.admin_panel_settings, size: 16, color: Colors.blue),
                SizedBox(width: 4),
                Text(
                  'Dibuat oleh petugas desa',
                  style: TextStyle(fontSize: 12, color: Colors.blue),
                ),
              ],
            ),
          ),
      ],
    ),
  );
}
```

---

## 🔐 6. Fitur Update Password

Sekarang warga dapat mengubah password mereka sendiri melalui aplikasi mobile ketika sudah login. Fitur ini memerlukan password lama untuk verifikasi keamanan.

### Request

```http
PUT /api/auth/update-password
Authorization: Bearer {token}
Content-Type: application/json
```

### Body Parameters

| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| `password_lama` | string | ✅ | Password lama yang sedang digunakan |
| `password_baru` | string | ✅ | Password baru minimal 8 karakter |
| `password_baru_confirmation` | string | ✅ | Konfirmasi password baru (harus sama dengan password_baru) |

### Response Sukses (200)

```json
{
  "message": "Password berhasil diperbarui."
}
```

### Response Error - Password Lama Salah (422)

```json
{
  "message": "The password lama field is invalid.",
  "errors": {
    "password_lama": ["Password lama tidak sesuai."]
  }
}
```

### Response Error - Validasi Gagal (422)

```json
{
  "message": "The password baru field must be at least 8 characters.",
  "errors": {
    "password_baru": ["Password baru minimal 8 karakter."],
    "password_baru_confirmation": ["Konfirmasi password baru tidak cocok."]
  }
}
```

### Response Error - Unauthenticated (401)

```json
{
  "message": "Unauthenticated."
}
```

### Contoh Implementasi (Flutter + Dio)

```dart
import 'package:dio/dio.dart';

class AuthService {
  final Dio _dio;
  final FlutterSecureStorage _storage;

  AuthService(this._dio, this._storage);

  Future<void> updatePassword({
    required String passwordLama,
    required String passwordBaru,
    required String passwordBaruConfirmation,
  }) async {
    try {
      final token = await _storage.read(key: 'auth_token');
      
      final response = await _dio.put(
        '/auth/update-password',
        data: {
          'password_lama': passwordLama,
          'password_baru': passwordBaru,
          'password_baru_confirmation': passwordBaruConfirmation,
        },
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );
      
      if (response.statusCode == 200) {
        print('Success: ${response.data['message']}');
        // Tampilkan snackbar sukses
        // Optional: Logout dan minta login ulang dengan password baru
      }
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        // Validation error atau password lama salah
        final errors = e.response?.data['errors'];
        if (errors?['password_lama'] != null) {
          throw Exception('Password lama tidak sesuai');
        } else if (errors?['password_baru'] != null) {
          throw Exception('Password baru minimal 8 karakter');
        } else if (errors?['password_baru_confirmation'] != null) {
          throw Exception('Konfirmasi password tidak cocok');
        }
      } else if (e.response?.statusCode == 401) {
        // Token invalid, perlu login ulang
        throw Exception('Sesi Anda telah berakhir. Silakan login kembali.');
      }
      rethrow;
    }
  }
}
```

### Contoh UI Form (Flutter)

```dart
class ChangePasswordPage extends StatefulWidget {
  @override
  _ChangePasswordPageState createState() => _ChangePasswordPageState();
}

class _ChangePasswordPageState extends State<ChangePasswordPage> {
  final _formKey = GlobalKey<FormState>();
  final _passwordLamaController = TextEditingController();
  final _passwordBaruController = TextEditingController();
  final _passwordKonfirmasiController = TextEditingController();
  
  bool _isLoading = false;
  bool _obscurePasswordLama = true;
  bool _obscurePasswordBaru = true;
  bool _obscurePasswordKonfirmasi = true;

  Future<void> _submitChangePassword() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      await AuthService(dio, storage).updatePassword(
        passwordLama: _passwordLamaController.text,
        passwordBaru: _passwordBaruController.text,
        passwordBaruConfirmation: _passwordKonfirmasiController.text,
      );

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Password berhasil diubah'),
          backgroundColor: Colors.green,
        ),
      );

      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(e.toString()),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Ubah Password'),
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Info Box
              Container(
                padding: EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.blue.shade50,
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: Colors.blue.shade200),
                ),
                child: Row(
                  children: [
                    Icon(Icons.info_outline, color: Colors.blue),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Password baru minimal 8 karakter',
                        style: TextStyle(
                          fontSize: 14,
                          color: Colors.blue.shade900,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              SizedBox(height: 24),

              // Password Lama
              TextFormField(
                controller: _passwordLamaController,
                obscureText: _obscurePasswordLama,
                decoration: InputDecoration(
                  labelText: 'Password Lama',
                  border: OutlineInputBorder(),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscurePasswordLama
                          ? Icons.visibility_off
                          : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() {
                        _obscurePasswordLama = !_obscurePasswordLama;
                      });
                    },
                  ),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password lama wajib diisi';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),

              // Password Baru
              TextFormField(
                controller: _passwordBaruController,
                obscureText: _obscurePasswordBaru,
                decoration: InputDecoration(
                  labelText: 'Password Baru',
                  border: OutlineInputBorder(),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscurePasswordBaru
                          ? Icons.visibility_off
                          : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() {
                        _obscurePasswordBaru = !_obscurePasswordBaru;
                      });
                    },
                  ),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password baru wajib diisi';
                  }
                  if (value.length < 8) {
                    return 'Password baru minimal 8 karakter';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),

              // Konfirmasi Password Baru
              TextFormField(
                controller: _passwordKonfirmasiController,
                obscureText: _obscurePasswordKonfirmasi,
                decoration: InputDecoration(
                  labelText: 'Konfirmasi Password Baru',
                  border: OutlineInputBorder(),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscurePasswordKonfirmasi
                          ? Icons.visibility_off
                          : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() {
                        _obscurePasswordKonfirmasi = !_obscurePasswordKonfirmasi;
                      });
                    },
                  ),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Konfirmasi password wajib diisi';
                  }
                  if (value != _passwordBaruController.text) {
                    return 'Konfirmasi password tidak cocok';
                  }
                  return null;
                },
              ),
              SizedBox(height: 24),

              // Submit Button
              ElevatedButton(
                onPressed: _isLoading ? null : _submitChangePassword,
                style: ElevatedButton.styleFrom(
                  padding: EdgeInsets.symmetric(vertical: 16),
                ),
                child: _isLoading
                    ? SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                        ),
                      )
                    : Text(
                        'Ubah Password',
                        style: TextStyle(fontSize: 16),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _passwordLamaController.dispose();
    _passwordBaruController.dispose();
    _passwordKonfirmasiController.dispose();
    super.dispose();
  }
}
```

### ⚠️ Catatan Penting

- **Password lama wajib** untuk verifikasi keamanan - user tidak bisa ubah password tanpa tahu password lama
- **Password baru minimal 8 karakter** - sesuai standar Laravel Password validation
- **Konfirmasi password harus cocok** dengan password baru
- **Token harus valid** - jika token expired, redirect ke login
- **Rekomendasi UX:** Setelah berhasil ubah password, logout user dan minta login ulang dengan password baru untuk memastikan mereka ingat password baru
- **Jika lupa password:** Warga harus menghubungi admin desa atau kecamatan untuk reset password melalui website admin

### Alur Lupa Password

```
Warga Lupa Password
    ↓
Hubungi Admin Desa/Kecamatan
    ↓
Admin Login ke Website
    ↓
Menu "Manajemen Warga"
    ↓
Cari Warga → Klik "Reset Password"
    ↓
Admin Set Password Baru
    ↓
Admin Beritahu Password Baru ke Warga
    ↓
Warga Login dengan Password Baru
    ↓
(Optional) Warga Ubah Password Sendiri
```

---

## 🧪 5. Testing di Lingkungan Development

### Base URL

```
https://api.sirama.com
```

### Swagger UI (Dokumentasi Interaktif)

```
https://api.sirama.com/api/documentation
```

### Cara Authenticate di Swagger

1. Buka **POST /api/login**
2. Gunakan kredensial testing (lihat di bawah)
3. Copy `access_token` dari response
4. Klik tombol **"Authorize"** di pojok kanan atas
5. Masukkan: `Bearer {access_token}`
6. Sekarang semua endpoint bisa di-test

### Akun Testing (dari Seeder)

| Role | Email | Password | NIK |
|------|-------|----------|-----|
| Admin | admin@example.com | password | - |
| Kecamatan | kecamatan@example.com | password | - |
| Desa | desa1@example.com | password | - |
| Warga | warga1@example.com | password | 3201234567890001 |
| Warga | warga2@example.com | password | 3201234567890002 |

---

## ✅ Checklist Update Frontend

### Halaman Baru

- [ ] Buat halaman **Form KK Baru**
  - [ ] 7 input file upload
  - [ ] Input text: nama, NIK, nomor HP, email, keterangan
  - [ ] Progress indicator untuk upload
  - [ ] Validasi ukuran file (max 2MB per file)

- [ ] Buat halaman **Ubah Password**
  - [ ] Input password lama (with visibility toggle)
  - [ ] Input password baru (with visibility toggle)
  - [ ] Input konfirmasi password baru (with visibility toggle)
  - [ ] Validasi password minimal 8 karakter
  - [ ] Validasi konfirmasi password cocok
  - [ ] Loading state saat submit
  - [ ] Handle error: password lama salah, validasi gagal, token expired
  
- [ ] Tambah routing ke halaman KK Baru
  - [ ] Dari dashboard/menu utama
  - [ ] Dari list jenis layanan

- [ ] Tambah routing ke halaman Ubah Password
  - [ ] Dari menu profil/settings
  - [ ] Atau dari drawer/sidebar

### Update Data Model

- [ ] Tambah `kk_baru` ke enum `JenisLayanan`
- [ ] Tambah `perlu_perbaikan` ke enum `StatusPengajuan`
- [ ] Tambah field `catatan_perbaikan` di model `Pengajuan`
- [ ] Tambah field `dibuat_oleh_admin_desa` di model `Pengajuan`

### Update UI Existing

- [ ] Update dropdown jenis perbaikan KK (+ "Perbaikan Pendidikan")
- [ ] Update mapping label jenis layanan
- [ ] Update ikon per jenis layanan
- [ ] Update stepper/progress indicator (handle status `perlu_perbaikan`)
- [ ] Tambah tombol "Perbaiki Pengajuan" untuk status `perlu_perbaikan`
- [ ] Tampilkan `catatan_perbaikan` jika ada
- [ ] Tampilkan badge "Dibuat oleh petugas" jika `dibuat_oleh_admin_desa` = true

### Testing

- [ ] Test submit pengajuan KK Baru
- [ ] Test upload 7 file sekaligus
- [ ] Test handle error validasi file (ukuran, format)
- [ ] Test tampilan status `perlu_perbaikan`
- [ ] Test edit & submit ulang pengajuan yang perlu perbaikan
- [ ] Test semua status di stepper
- [ ] Test tampilan pengajuan yang dibuat admin desa
- [ ] Test ubah password dengan password lama yang benar
- [ ] Test ubah password dengan password lama yang salah
- [ ] Test validasi password baru (minimal 8 karakter)
- [ ] Test validasi konfirmasi password tidak cocok
- [ ] Test ubah password dengan token expired (handle 401)
- [ ] Test logout setelah berhasil ubah password (optional UX)

---

## 🛡️ Defensive Coding

### Handle Nilai Status Tidak Dikenal

```dart
String getStatusLabel(String status) {
  const statusMap = {
    'menunggu_verifikasi_desa': 'Menunggu Verifikasi Desa',
    'perlu_perbaikan': 'Perlu Perbaikan',
    'menunggu_verifikasi_kecamatan': 'Menunggu Verifikasi Kecamatan',
    'menunggu_verifikasi_admin': 'Menunggu Verifikasi Admin',
    'selesai': 'Selesai',
    'ditolak': 'Ditolak',
  };
  
  return statusMap[status] ?? 'Status Tidak Dikenal ($status)';
}
```

### Handle Jenis Layanan Tidak Dikenal

```dart
Widget buildJenisLayananIcon(String jenisLayanan) {
  IconData icon;
  
  switch (jenisLayanan) {
    case 'akta_lahir':
      icon = Icons.baby_changing_station;
      break;
    case 'kk_baru':
      icon = Icons.family_restroom;
      break;
    // ... other cases
    default:
      icon = Icons.description; // Fallback icon
      print('⚠️ Jenis layanan tidak dikenal: $jenisLayanan');
  }
  
  return Icon(icon);
}
```

### Validasi Sebelum Crash

```dart
// ❌ JANGAN seperti ini
Text(pengajuan.catatanPerbaikan),

// ✅ Lakukan seperti ini
Text(pengajuan.catatanPerbaikan ?? 'Tidak ada catatan'),

// ✅ Atau seperti ini
if (pengajuan.catatanPerbaikan != null) {
  Text(pengajuan.catatanPerbaikan!);
}
```

---

## 📞 Kontak & Support

| Keperluan | Kontak |
|-----------|--------|
| Bug report API | backend-team@sirama.com |
| Pertanyaan teknis | Slack: #sirama-dev |
| Akses staging | devops@sirama.com |
| Dokumentasi lengkap | [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) |

---

## 📚 Referensi Tambahan

- **Full API Documentation:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- **Swagger UI:** https://api.sirama.com/api/documentation
- **Postman Collection:** (request ke backend team)

---

**Catatan:** Dokumentasi ini berlaku mulai **Januari 2025**. Jika ada perubahan lebih lanjut, file ini akan diupdate.

---

*Generated: 2025-01-15*  
*Maintainer: Backend Team SIRAMA*
