<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormKkPenambahanFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'nama_kepala_keluarga' => fake()->word(),
            'nomor_kk' => fake()->word(),
            'alamat' => fake()->text(),
            'nama_dusun' => fake()->word(),
            'rt' => fake()->word(),
            'rw' => fake()->word(),
            'nama_ketua_rt' => fake()->word(),
            'nama_ketua_rw' => fake()->word(),
            'nama_lengkap_tambahan' => fake()->word(),
            'jenis_kelamin_tambahan' => fake()->randomElement(["L","P"]),
            'tempat_lahir_tambahan' => fake()->word(),
            'tanggal_lahir_tambahan' => fake()->date(),
            'status_hubungan' => fake()->word(),
            'kelainan_fisik_mental' => fake()->word(),
            'penyandang_cacat' => fake()->word(),
            'agama' => fake()->word(),
            'nama_ibu_kandung' => fake()->word(),
            'nik_ibu' => fake()->word(),
            'nama_ayah_kandung' => fake()->word(),
            'nik_ayah' => fake()->word(),
            'file_kk_asli' => fake()->word(),
            'file_sk_lahir_akta' => fake()->word(),
            'file_ktp_suami_istri' => fake()->word(),
            'file_surat_nikah' => fake()->word(),
        ];
    }
}
