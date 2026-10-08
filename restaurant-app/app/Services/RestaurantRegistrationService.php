<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Plan;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RestaurantRegistrationService
{
    public function register(array $data): Restaurant
    {
        return DB::transaction(function () use ($data) {
            $restaurant = Restaurant::create([
                'id' => $data['subdomain'],
                'name' => $data['company_name'],
            ]);

            $restaurant->domains()->create([
                'domain' => $data['subdomain'].'.localhost',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'restaurant_id' => $restaurant->id,
            ]);

            $role = Role::firstOrCreate(
                ['name' => 'admin', 'guard_name' => 'web'],
                ['name' => 'admin', 'guard_name' => 'web']
            );

            $user->assignRole($role);

            Subscription::create([
                'restaurant_id' => $restaurant->id,
                'plan' => Plan::FreeTrial->value,
                'status' => SubscriptionStatus::Trialing->value,
                'trial_ends_at' => now()->addDays(Plan::FreeTrial->trialDays()),
            ]);

            return $restaurant;
        });
    }
}
