<?php

namespace Database\Factories;

use App\Models\WaiterProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaiterProfileFactory extends Factory
{
    protected $model = WaiterProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }
}
