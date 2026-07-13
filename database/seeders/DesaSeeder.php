<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $desas = [
            'Desa Sukosari Lor',
            'Desa Nogosari',
            'Desa Kerang',
            'Desa Pecalongan',
        ];

        foreach ($desas as $nama) {
            Desa::firstOrCreate(
                ['nama' => $nama],
                ['kecamatan' => 'Sukosari']
            );
        }
    }
}
