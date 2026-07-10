<?php

namespace Database\Factories;

use App\Models\WasteRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteRecordFactory extends Factory
{
    protected $model = WasteRecord::class;

    public function definition(): array
    {
        return [
            'dish_id' => \App\Models\Dish::factory(),
            'registered_by' => \App\Models\User::factory(),
            'quantity' => fake()->numberBetween(1, 3),
            'reason' => fake()->randomElement(['overcooked', 'mistake', 'returned', 'spoiled', 'overproduction', 'other']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
