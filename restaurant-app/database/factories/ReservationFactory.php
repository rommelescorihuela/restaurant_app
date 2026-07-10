<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'table_id' => fake()->optional(0.7)->passthrough(Table::factory()),
            'reservation_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'guest_count' => fake()->numberBetween(1, 8),
            'status' => fake()->randomElement(['pending', 'confirmed', 'confirmed', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
