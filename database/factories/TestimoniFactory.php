<?php
// database/factories/TestimoniFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimoniFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'isi'     => $this->faker->paragraph(),
            'rating'  => $this->faker->numberBetween(1, 5),
            'foto'    => null,
        ];
    }
}