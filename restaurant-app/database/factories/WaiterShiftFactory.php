<?php

namespace Database\Factories;

use App\Models\WaiterShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaiterShiftFactory extends Factory
{
    protected $model = WaiterShift::class;

    public function definition(): array
    {
        return [
            'waiter_id' => \App\Models\User::factory(),
            'started_at' => now()->subHours(4),
            'status' => 'active',
            'is_on_break' => false,
        ];
    }
}
