<?php

namespace App\Console\Commands;

use App\Models\Incident;
use App\Models\Table;
use App\Models\User;
use App\Notifications\ServiceAlert;
use Illuminate\Console\Command;

class DetectAbandonedTables extends Command
{
    protected $signature = 'mesoneros:detect-abandoned {--minutes=15 : Minutos sin actividad para considerar abandonada}';

    protected $description = 'Detecta mesas sin actividad y genera alertas';

    public function handle(): void
    {
        $threshold = now()->subMinutes((int) $this->option('minutes'));

        $tables = Table::where('is_active', true)
            ->where('alerted_abandoned', false)
            ->whereDoesntHave('orders', function ($q) use ($threshold) {
                $q->where('created_at', '>=', $threshold);
            })
            ->get();

        foreach ($tables as $table) {
            $incident = Incident::create([
                'table_id' => $table->id,
                'type' => 'table_abandoned',
                'description' => "Mesa {$table->number} sin actividad por más de {$this->option('minutes')} minutos",
                'status' => 'open',
            ]);

            $table->update(['alerted_abandoned' => true]);

            $jefes = User::role(['super_admin', 'admin'])->get();
            foreach ($jefes as $jefe) {
                $jefe->notify(new ServiceAlert(
                    table: $table,
                    waiter: $incident->waiter ?? $jefe,
                    type: 'table_abandoned',
                ));
            }

            $this->info("Alerta generada para mesa {$table->number}");
        }

        if ($tables->isEmpty()) {
            $this->info('No se detectaron mesas abandonadas.');
        }
    }
}
