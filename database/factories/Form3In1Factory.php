<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class Form3In1Factory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'nama_lengkap_pemohon' => fake()->word(),
            'desa' => fake()->word(),
            'alamat_lengkap' => fake()->text(),
            'nama_anak' => fake()->word(),
            'tanggal_lahir_anak' => fake()->date(),
            'file_sk_lahir' => fake()->word(),
            'file_kk' => fake()->word(),
            'file_ktp_ortu' => fake()->word(),
            'file_surat_nikah' => fake()->word(),
            'file_foto_anak' => fake()->word(),
        ];
    }
}
