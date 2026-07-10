<?php

namespace Database\Factories;

use App\Models\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition(): array
    {
        return [
            'table_id' => \App\Models\Table::factory(),
            'waiter_id' => \App\Models\User::factory(),
            'type' => fake()->randomElement(['needs_help', 'spill', 'unhappy_customer', 'damaged_table', 'other']),
            'description' => fake()->sentence(),
            'status' => 'open',
        ];
    }
}
