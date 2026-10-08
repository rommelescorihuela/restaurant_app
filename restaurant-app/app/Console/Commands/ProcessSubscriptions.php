<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ProcessSubscriptions extends Command
{
    protected $signature = 'subscriptions:process';

    protected $description = 'Procesa el ciclo de vida de las suscripciones (vencimientos de trial y periodos)';

    public function handle(): int
    {
        $this->info('Procesando suscripciones...');

        $now = now();

        $expiredTrials = Subscription::where('status', SubscriptionStatus::Trialing->value)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', $now)
            ->get();

        foreach ($expiredTrials as $subscription) {
            $subscription->update(['status' => SubscriptionStatus::Suspended->value]);
            $this->warn("Trial expirado: restaurante {$subscription->restaurant_id} suspendido.");
        }

        $expiredPeriods = Subscription::where('status', SubscriptionStatus::Active->value)
            ->whereNotNull('current_period_end')
            ->where('current_period_end', '<', $now->toDateString())
            ->get();

        foreach ($expiredPeriods as $subscription) {
            $subscription->update(['status' => SubscriptionStatus::PastDue->value]);
            $this->warn("Periodo vencido: restaurante {$subscription->restaurant_id} marcado como pago pendiente.");
        }

        $this->info("Procesadas {$expiredTrials->count()} trials y {$expiredPeriods->count()} periodos vencidos.");

        return self::SUCCESS;
    }
}
