<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MasterJenisPerbaikanKkFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama_perbaikan' => fake()->word(),
            'deskripsi' => fake()->text(),
        ];
    }
}
