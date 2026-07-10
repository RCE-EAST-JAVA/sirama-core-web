<?php

namespace Database\Factories;

use App\Models\MasterJenisPerbaikanKk;
use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormKkPerbaikanFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'jenis_perbaikan_id' => MasterJenisPerbaikanKk::factory(),
            'nama_kepala_keluarga' => fake()->word(),
            'nomor_kk' => fake()->word(),
            'nama_anggota_yang_diperbaiki' => fake()->word(),
            'data_perbaikan' => '{}',
            'file_pendukung' => '{}',
        ];
    }
}
