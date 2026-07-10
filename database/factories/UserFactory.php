<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nik' => fake()->word(),
            'name' => fake()->name(),
            'no_whatsapp' => fake()->word(),
            'password' => fake()->password(),
            'role' => fake()->randomElement(["warga","admin_desa","admin_kecamatan"]),
            'rememberToken' => fake()->word(),
        ];
    }
}
