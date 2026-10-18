<?php

namespace App\Services;

use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use App\Models\User;
use App\Notifications\PengajuanSiapDiprosesNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DesaPengajuanStoreService
{
    /**
     * Cari user warga berdasarkan NIK.
     * Jika belum ada, buat akun warga baru secara otomatis.
     * Akun ini tidak dimaksudkan untuk login mandiri oleh warga.
     */
    public function createOrFindWarga(array $pemohon, string $desa): User
    {
        return User::firstOrCreate(
            ['nik' => $pemohon['nik'], 'role' => 'warga'],
            [
                'name'         => $pemohon['nama_lengkap'],
                'no_whatsapp'  => $pemohon['no_whatsapp'],
                'password'     => Hash::make(Str::random(32)),
                'role'         => 'warga',
                'desa'         => $desa,
                'tanggal_lahir'=> $pemohon['tanggal_lahir'],
                'jenis_kelamin'=> $pemohon['jenis_kelamin'],
                'pekerjaan'    => $pemohon['pekerjaan'],
                'alamat'       => $pemohon['alamat'],
                'rt'           => $pemohon['rt'],
                'rw'           => $pemohon['rw'],
            ]
        );
    }

    /**
     * Bangun data snapshot pengajuan dari input manual admin desa.
     * Status langsung diverifikasi_desa karena admin desa yang membuat.
     */
    public function buildPengajuanData(User $warga, string $jenisLayanan, array $pemohon, string $desa): array
    {
        return [
            'user_id'       => $warga->id,
            'jenis_layanan' => $jenisLayanan,
            'status'        => 'diverifikasi_desa',
            'nama_lengkap'  => $pemohon['nama_lengkap'],
            'nik'           => $pemohon['nik'],
            'no_whatsapp'   => $pemohon['no_whatsapp'],
            'tanggal_lahir' => $pemohon['tanggal_lahir'],
            'jenis_kelamin' => $pemohon['jenis_kelamin'],
            'pekerjaan'     => $pemohon['pekerjaan'],
            'alamat'        => $pemohon['alamat'],
            'desa'          => $desa,
            'rt'            => $pemohon['rt'],
            'rw'            => $pemohon['rw'],
        ];
    }

    /**
     * Catat riwayat status awal pengajuan yang dibuat oleh admin desa.
     */
    public function catatRiwayat(Pengajuan $pengajuan): void
    {
        RiwayatStatus::create([
            'pengajuan_id'   => $pengajuan->id,
            'status_riwayat' => 'diverifikasi_desa',
            'catatan'        => 'Pengajuan dibuat dan diverifikasi langsung oleh Admin Desa.',
        ]);
    }

    /**
     * Simpan file upload ke storage lokal (private disk).
     */
    public function storeFile(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'local');
    }

    /**
     * Kirim notifikasi ke semua admin kecamatan bahwa ada pengajuan siap diproses.
     */
    public function notifyKecamatan(Pengajuan $pengajuan): void
    {
        $adminKecamatan = User::where('role', 'admin_kecamatan')->get();

        foreach ($adminKecamatan as $admin) {
            $admin->notify(new PengajuanSiapDiprosesNotification($pengajuan));
        }
    }
}
