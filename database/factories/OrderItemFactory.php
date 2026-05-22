<?php
// database/factories/OrderItemFactory.php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'menu_id'  => Menu::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'price'    => $this->faker->numberBetween(10000, 50000),
        ];
    }
}