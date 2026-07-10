<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormKiaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'nama_lengkap' => fake()->word(),
            'tempat_lahir' => fake()->word(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(["L","P"]),
            'nama_kepala_keluarga' => fake()->word(),
            'agama' => fake()->word(),
            'kewarganegaraan' => fake()->word(),
            'file_akta_kelahiran' => fake()->word(),
            'file_kk' => fake()->word(),
            'file_surat_nikah' => fake()->word(),
            'file_foto_anak' => fake()->word(),
        ];
    }
}
