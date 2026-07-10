<?php

namespace Database\Factories;

use App\Models\WaiterAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaiterAssignmentFactory extends Factory
{
    protected $model = WaiterAssignment::class;

    public function definition(): array
    {
        return [
            'table_id' => \App\Models\Table::factory(),
            'waiter_id' => \App\Models\User::factory(),
            'zone_id' => null,
            'is_primary' => true,
            'status' => 'active',
            'assigned_at' => now(),
        ];
    }
}
