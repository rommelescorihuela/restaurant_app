<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Plan;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\Subscription;

class SubscriptionService
{
    public function createTrial(Restaurant $restaurant): Subscription
    {
        return Subscription::create([
            'restaurant_id' => $restaurant->id,
            'plan' => Plan::FreeTrial->value,
            'status' => SubscriptionStatus::Trialing->value,
            'trial_ends_at' => now()->addDays(Plan::FreeTrial->trialDays()),
        ]);
    }

    public function activate(Restaurant $restaurant, Plan $plan): Subscription
    {
        $subscription = $restaurant->subscription ?? $this->createTrial($restaurant);

        $subscription->update([
            'plan' => $plan->value,
            'status' => SubscriptionStatus::Active->value,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'seats_limit' => $plan->seatsLimit(),
            'cancelled_at' => null,
        ]);

        return $subscription;
    }

    public function markPastDue(Restaurant $restaurant): Subscription
    {
        $subscription = $restaurant->subscription;
        $subscription->update(['status' => SubscriptionStatus::PastDue->value]);

        return $subscription;
    }

    public function suspend(Restaurant $restaurant): Subscription
    {
        $subscription = $restaurant->subscription;
        $subscription->update(['status' => SubscriptionStatus::Suspended->value]);

        return $subscription;
    }

    public function cancel(Restaurant $restaurant): Subscription
    {
        $subscription = $restaurant->subscription;
        $subscription->update([
            'status' => SubscriptionStatus::Cancelled->value,
            'cancelled_at' => now(),
        ]);

        return $subscription;
    }
}
