<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'status_riwayat' => fake()->word(),
            'catatan' => fake()->text(),
        ];
    }
}
