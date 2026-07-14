<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'jenis_layanan' => fake()->randomElement(["kia","3_in_1","kk_penambahan","kk_pengurangan","kk_perbaikan","akta_kelahiran","akta_kematian"]),
            'status' => fake()->randomElement(["berkas_diterima","diverifikasi_desa","diverifikasi_kecamatan","selesai"]),
            'lokasi_dokumen' => fake()->word(),
            'no_whatsapp' => fake()->word(),
        ];
    }
}
