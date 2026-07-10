<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormKkPenguranganFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'alasan_pengurangan' => fake()->text(),
            'nama_lengkap_anggota' => fake()->word(),
            'alamat_lengkap_anggota' => fake()->text(),
            'nik_anggota' => fake()->word(),
            'file_kk_asli' => fake()->word(),
            'file_ktp_asli' => fake()->word(),
            'file_sk_pindah_mati' => fake()->word(),
        ];
    }
}
