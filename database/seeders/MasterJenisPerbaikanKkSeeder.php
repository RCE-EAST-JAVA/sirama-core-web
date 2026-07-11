<?php

namespace Database\Seeders;

use App\Models\MasterJenisPerbaikanKk;
use Illuminate\Database\Seeder;

class MasterJenisPerbaikanKkSeeder extends Seeder
{
    public function run(): void
    {
        $jenisPerbaikan = [
            ['nama_perbaikan' => 'Perbaikan Nama',           'deskripsi' => 'Koreksi ejaan atau penulisan nama anggota keluarga'],
            ['nama_perbaikan' => 'Perbaikan Tempat Lahir',   'deskripsi' => 'Koreksi nama kota/kabupaten tempat lahir'],
            ['nama_perbaikan' => 'Perbaikan Tanggal Lahir',  'deskripsi' => 'Koreksi tanggal, bulan, atau tahun lahir'],
            ['nama_perbaikan' => 'Perbaikan Agama',          'deskripsi' => 'Koreksi data agama anggota keluarga'],
            ['nama_perbaikan' => 'Perbaikan Status Kawin',   'deskripsi' => 'Koreksi status perkawinan anggota keluarga'],
            ['nama_perbaikan' => 'Perbaikan Pekerjaan',      'deskripsi' => 'Koreksi data pekerjaan anggota keluarga'],
            ['nama_perbaikan' => 'Perbaikan Kewarganegaraan','deskripsi' => 'Koreksi data kewarganegaraan anggota keluarga'],
            ['nama_perbaikan' => 'Perbaikan Alamat',         'deskripsi' => 'Koreksi data alamat pada Kartu Keluarga'],
        ];

        foreach ($jenisPerbaikan as $item) {
            MasterJenisPerbaikanKk::create($item);
        }
    }
}
