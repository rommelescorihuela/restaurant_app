<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'table_id' => \App\Models\Table::factory(),
            'waiter_id' => \App\Models\User::factory(),
            'status' => 'pending',
            'priority' => 'normal',
            'total' => fake()->randomFloat(2, 10, 200),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
