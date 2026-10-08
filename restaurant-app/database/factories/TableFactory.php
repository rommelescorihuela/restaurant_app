<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => (string) fake()->unique()->numberBetween(1, 30),
            'capacity' => fake()->randomElement([2, 4, 4, 6, 8]),
            'location' => fake()->randomElement(['Interior', 'Terraza', 'VIP', 'Jardín']),
            'is_active' => true,
        ];
    }
}
