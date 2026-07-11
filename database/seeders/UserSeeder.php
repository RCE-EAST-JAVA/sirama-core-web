<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Aplikasi
        User::create([
            'nik'         => '0000000000000001',
            'name'        => 'Admin Aplikasi',
            'no_whatsapp' => '081200000001',
            'password'    => Hash::make('password'),
            'role'        => 'admin_aplikasi',
            'desa'        => null,
        ]);

        // Admin Kecamatan
        User::create([
            'nik'         => '0000000000000002',
            'name'        => 'Admin Kecamatan Cimahi',
            'no_whatsapp' => '081200000002',
            'password'    => Hash::make('password'),
            'role'        => 'admin_kecamatan',
            'desa'        => null,
        ]);

        // Admin Desa — 3 desa berbeda
        $desaList = [
            ['nik' => '0000000000000003', 'name' => 'Admin Desa Cibabat',   'desa' => 'Cibabat',   'no_wa' => '081200000003'],
            ['nik' => '0000000000000004', 'name' => 'Admin Desa Cimahi',    'desa' => 'Cimahi',    'no_wa' => '081200000004'],
            ['nik' => '0000000000000005', 'name' => 'Admin Desa Padasuka',  'desa' => 'Padasuka',  'no_wa' => '081200000005'],
        ];

        foreach ($desaList as $desa) {
            User::create([
                'nik'         => $desa['nik'],
                'name'        => $desa['name'],
                'no_whatsapp' => $desa['no_wa'],
                'password'    => Hash::make('password'),
                'role'        => 'admin_desa',
                'desa'        => $desa['desa'],
            ]);
        }

        // Warga contoh
        $wargaList = [
            ['nik' => '3277010101900001', 'name' => 'Budi Santoso',    'desa' => 'Cibabat',  'no_wa' => '081211110001'],
            ['nik' => '3277010101900002', 'name' => 'Siti Rahayu',     'desa' => 'Cibabat',  'no_wa' => '081211110002'],
            ['nik' => '3277010101900003', 'name' => 'Ahmad Fauzi',     'desa' => 'Cimahi',   'no_wa' => '081211110003'],
            ['nik' => '3277010101900004', 'name' => 'Dewi Lestari',    'desa' => 'Padasuka', 'no_wa' => '081211110004'],
            ['nik' => '3277010101900005', 'name' => 'Hendra Gunawan',  'desa' => 'Cibabat',  'no_wa' => '081211110005'],
        ];

        foreach ($wargaList as $warga) {
            User::create([
                'nik'         => $warga['nik'],
                'name'        => $warga['name'],
                'no_whatsapp' => $warga['no_wa'],
                'password'    => Hash::make('password'),
                'role'        => 'warga',
                'desa'        => $warga['desa'],
            ]);
        }
    }
}
