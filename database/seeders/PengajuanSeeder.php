<?php

namespace Database\Seeders;

use App\Models\Form3In1;
use App\Models\FormKia;
use App\Models\FormKkPenambahan;
use App\Models\FormKkPengurangan;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $warga = User::where('role', 'warga')->get();

        if ($warga->isEmpty()) {
            $this->command->warn('Tidak ada user warga. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // 1. Pengajuan KIA — status: berkas_diterima
        $p1 = Pengajuan::create([
            'user_id'       => $warga[0]->id,
            'jenis_layanan' => 'kia',
            'status'        => 'berkas_diterima',
            'no_whatsapp'   => $warga[0]->no_whatsapp,
        ]);
        FormKia::create([
            'pengajuan_id'        => $p1->id,
            'nama_lengkap'        => 'Anak Budi Santoso',
            'tempat_lahir'        => 'Cimahi',
            'tanggal_lahir'       => '2022-03-15',
            'jenis_kelamin'       => 'L',
            'nama_kepala_keluarga'=> 'Budi Santoso',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'file_akta_kelahiran' => 'dummy/akta_kelahiran.jpg',
            'file_kk'             => 'dummy/kk.jpg',
            'file_surat_nikah'    => 'dummy/surat_nikah.jpg',
            'file_foto_anak'      => 'dummy/foto_anak.jpg',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p1->id,
            'status_riwayat' => 'berkas_diterima',
            'catatan'        => 'Pengajuan dibuat oleh pemohon.',
        ]);

        // 2. Pengajuan 3 in 1 — status: diverifikasi_desa
        $p2 = Pengajuan::create([
            'user_id'       => $warga[1]->id,
            'jenis_layanan' => '3_in_1',
            'status'        => 'diverifikasi_desa',
            'no_whatsapp'   => $warga[1]->no_whatsapp,
        ]);
        Form3In1::create([
            'pengajuan_id'       => $p2->id,
            'nama_anak'          => 'Bayi Siti Rahayu',
            'tanggal_lahir_anak' => '2023-08-20',
            'file_sk_lahir'      => 'dummy/sk_lahir.jpg',
            'file_kk'            => 'dummy/kk.jpg',
            'file_ktp_ortu'      => 'dummy/ktp_ortu.jpg',
            'file_surat_nikah'   => 'dummy/surat_nikah.jpg',
            'file_foto_anak'     => 'dummy/foto_anak.jpg',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p2->id,
            'status_riwayat' => 'berkas_diterima',
            'catatan'        => 'Pengajuan dibuat oleh pemohon.',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p2->id,
            'status_riwayat' => 'diverifikasi_desa',
            'catatan'        => 'Berkas lengkap. Diteruskan ke kecamatan.',
        ]);

        // 3. Pengajuan KK Penambahan — status: diproses_kecamatan
        $p3 = Pengajuan::create([
            'user_id'       => $warga[2]->id,
            'jenis_layanan' => 'kk_penambahan',
            'status'        => 'diproses_kecamatan',
            'no_whatsapp'   => $warga[2]->no_whatsapp,
        ]);
        FormKkPenambahan::create([
            'pengajuan_id'          => $p3->id,
            'nama_kepala_keluarga'  => 'Ahmad Fauzi',
            'nomor_kk'              => '3277011234567890',
            'nama_ketua_rt'         => 'Pak RT Cimahi',
            'nama_ketua_rw'         => 'Pak RW Cimahi',
            'nama_lengkap_tambahan' => 'Bayi Ahmad Fauzi',
            'jenis_kelamin_tambahan'=> 'P',
            'tempat_lahir_tambahan' => 'Cimahi',
            'tanggal_lahir_tambahan'=> '2024-01-10',
            'status_hubungan'       => 'Anak',
            'kelainan_fisik_mental' => 'Tidak Ada',
            'penyandang_cacat'      => 'Tidak',
            'agama'                 => 'Islam',
            'nama_ibu_kandung'      => 'Istri Ahmad Fauzi',
            'nik_ibu'               => '3277010101920001',
            'nama_ayah_kandung'     => 'Ahmad Fauzi',
            'nik_ayah'              => $warga[2]->nik,
            'file_kk_asli'          => 'dummy/kk.jpg',
            'file_sk_lahir_akta'    => 'dummy/sk_lahir.jpg',
            'file_ktp_suami_istri'  => 'dummy/ktp_ortu.jpg',
            'file_surat_nikah'      => 'dummy/surat_nikah.jpg',
        ]);

        // 4. Pengajuan KK Pengurangan — status: ditolak_desa
        $p4 = Pengajuan::create([
            'user_id'       => $warga[3]->id,
            'jenis_layanan' => 'kk_pengurangan',
            'status'        => 'ditolak_desa',
            'no_whatsapp'   => $warga[3]->no_whatsapp,
        ]);
        FormKkPengurangan::create([
            'pengajuan_id'           => $p4->id,
            'alasan_pengurangan'     => 'Anggota keluarga telah meninggal dunia',
            'nama_lengkap_anggota'   => 'Orang Tua Dewi Lestari',
            'alamat_lengkap_anggota' => 'Jl. Padasuka No. 7',
            'nik_anggota'            => '3277010101500001',
            'file_kk_asli'           => 'dummy/kk.jpg',
            'file_ktp_asli'          => 'dummy/ktp_ortu.jpg',
            'file_sk_pindah_mati'    => 'dummy/sk_kematian.jpg',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p4->id,
            'status_riwayat' => 'berkas_diterima',
            'catatan'        => 'Pengajuan dibuat oleh pemohon.',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p4->id,
            'status_riwayat' => 'ditolak_desa',
            'catatan'        => 'Foto surat kematian kurang jelas. Mohon upload ulang.',
        ]);

        // 5. Pengajuan KIA — status: selesai
        $p5 = Pengajuan::create([
            'user_id'         => $warga[4]->id,
            'jenis_layanan'   => 'kia',
            'status'          => 'selesai',
            'lokasi_dokumen'  => 'softfile/5/kia_hendra_gunawan.pdf',
            'no_whatsapp'     => $warga[4]->no_whatsapp,
        ]);
        FormKia::create([
            'pengajuan_id'        => $p5->id,
            'nama_lengkap'        => 'Anak Hendra Gunawan',
            'tempat_lahir'        => 'Bandung',
            'tanggal_lahir'       => '2021-05-05',
            'jenis_kelamin'       => 'L',
            'nama_kepala_keluarga'=> 'Hendra Gunawan',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'file_akta_kelahiran' => 'dummy/akta_kelahiran.jpg',
            'file_kk'             => 'dummy/kk.jpg',
            'file_surat_nikah'    => 'dummy/surat_nikah.jpg',
            'file_foto_anak'      => 'dummy/foto_anak.jpg',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p5->id,
            'status_riwayat' => 'berkas_diterima',
            'catatan'        => 'Pengajuan dibuat oleh pemohon.',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p5->id,
            'status_riwayat' => 'diverifikasi_desa',
            'catatan'        => 'Berkas lengkap.',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p5->id,
            'status_riwayat' => 'diproses_kecamatan',
            'catatan'        => 'Sedang diproses.',
        ]);
        RiwayatStatus::create([
            'pengajuan_id'   => $p5->id,
            'status_riwayat' => 'selesai',
            'catatan'        => 'KIA selesai dibuat. Silakan unduh dokumen.',
        ]);
    }
}
