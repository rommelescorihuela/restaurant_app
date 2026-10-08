<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\KitchenAlert;
use Illuminate\Console\Command;

class CheckOverdueItems extends Command
{
    protected $signature = 'cocina:check-overdue
        {--minutes=15 : Minutos desde que un item está en preparación para considerarse en demora}';

    protected $description = 'Detecta items en cocina con demora y notifica al jefe de cocina';

    public function handle(): int
    {
        $threshold = max(1, (int) $this->option('minutes'));

        $overdue = OrderItem::where('status', 'preparing')
            ->where('started_at', '<', now()->subMinutes($threshold))
            ->with(['dish', 'order.table'])
            ->get();

        if ($overdue->isEmpty()) {
            $this->info('No hay items en demora.');
            return self::SUCCESS;
        }

        $chiefs = User::role(['super_admin', 'admin'])->get();

        foreach ($overdue as $item) {
            $minutes = (int) $item->started_at->diffInMinutes(now());

            foreach ($chiefs as $chief) {
                $chief->notify(new KitchenAlert(
                    type: 'overdue',
                    message: "{$item->dish->name} (Mesa {$item->order->table->number}) lleva {$minutes} min en preparación",
                    item: $item,
                ));
            }

            $this->line("Notificado: {$item->dish->name} - {$minutes} min");
        }

        $this->info("{$overdue->count()} item(s) en demora notificados.");

        return self::SUCCESS;
    }
}
