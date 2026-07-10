<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'dish_id' => \App\Models\Dish::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(2, 5, 50),
            'status' => 'pending',
            'modifiers' => fake()->optional()->word(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
