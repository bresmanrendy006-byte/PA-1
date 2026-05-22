<?php
// database/factories/CartFactory.php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'menu_id'  => Menu::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'notes'    => null,
        ];
    }
}