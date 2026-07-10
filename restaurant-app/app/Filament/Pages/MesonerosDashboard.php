<?php

namespace App\Filament\Pages;

use App\Models\Incident;
use App\Models\Table;
use App\Models\TableHistory;
use App\Models\User;
use App\Models\WaiterAssignment;
use App\Models\WaiterShift;
use App\Notifications\ServiceAlert;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MesonerosDashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.mesoneros-dashboard';

    protected static string | UnitEnum | null $navigationGroup = 'Mesoneros';

    public function getTitle(): string
    {
        return 'Dashboard de Mesoneros';
    }

    public function requestHelp(int $tableId): void
    {
        $table = Table::findOrFail($tableId);
        $waiter = User::findOrFail(auth()->id());

        $table->update(['help_requested_at' => now()]);

        Incident::create([
            'table_id' => $table->id,
            'waiter_id' => $waiter->id,
            'type' => 'needs_help',
            'description' => "Mesa {$table->number} solicita ayuda",
            'status' => 'open',
        ]);

        $this->notifyJefes(new ServiceAlert(
            table: $table,
            waiter: $waiter,
            type: 'help_request',
        ));

        Notification::make()
            ->title('Ayuda solicitada para mesa ' . $table->number)
            ->success()
            ->send();
    }

    public function resolveHelp(int $tableId): void
    {
        $table = Table::findOrFail($tableId);
        $table->update(['help_requested_at' => null]);

        Incident::where('table_id', $tableId)
            ->where('type', 'needs_help')
            ->where('status', 'open')
            ->update([
                'status' => 'resolved',
                'resolved_by' => auth()->id(),
                'resolved_at' => now(),
            ]);

        Notification::make()
            ->title('Ayuda atendida en mesa ' . $table->number)
            ->success()
            ->send();
    }

    public function getTableData(): array
    {
        $tables = Table::with([
            'zone', 'mergedTables',
            'assignments' => fn ($q) => $q->where('status', 'active')->with('waiter'),
            'orders' => fn ($q) => $q->latest()->limit(1),
        ])->where('is_active', true)->whereNull('merged_into_id')->get();

        $data = [];
        foreach ($tables as $table) {
            $lastOrder = $table->orders->first();
            $activeAssignment = $table->assignments->first();
            $minutesSinceLastOrder = $lastOrder ? now()->diffInMinutes($lastOrder->created_at) : null;

            $priority = 'normal';
            if ($table->help_requested_at) {
                $priority = 'help';
            } elseif ($lastOrder && $lastOrder->status === 'ready' && $minutesSinceLastOrder > 5) {
                $priority = 'urgent';
            } elseif ($minutesSinceLastOrder === null || $minutesSinceLastOrder > 10) {
                $priority = 'warning';
            }

            $data[] = [
                'id' => $table->id,
                'number' => $table->number,
                'capacity' => $table->capacity,
                'zone' => $table->zone?->name ?? 'Sin zona',
                'waiter' => $activeAssignment?->waiter?->name ?? 'Sin asignar',
                'waiter_id' => $activeAssignment?->waiter?->id,
                'last_order' => $minutesSinceLastOrder,
                'order_status' => $lastOrder?->status,
                'priority' => $priority,
                'help_requested' => $table->help_requested_at !== null,
                'location' => $table->location,
                'merged_tables' => $table->mergedTables->pluck('number')->toArray(),
                'has_merged' => $table->mergedTables->isNotEmpty(),
            ];
        }

        return $data;
    }

    public function getWaitersOnShift(): array
    {
        return WaiterShift::with('waiter')
            ->where('status', 'active')
            ->where('is_on_break', false)
            ->get()
            ->map(fn ($s) => [
                'id' => $s->waiter->id,
                'name' => $s->waiter->name,
                'tables_count' => WaiterAssignment::where('waiter_id', $s->waiter_id)
                    ->where('status', 'active')
                    ->count(),
            ])
            ->toArray();
    }

    public function getActiveAlerts(): array
    {
        return Incident::with(['table', 'waiter'])
            ->where('status', 'open')
            ->whereIn('type', ['needs_help', 'table_abandoned'])
            ->latest()
            ->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'table_id' => $i->table_id,
                'table_number' => $i->table->number,
                'waiter_name' => $i->waiter?->name ?? '—',
                'type' => $i->type,
                'description' => $i->description,
                'created_at' => $i->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public ?int $reassignTableId = null;

    public ?int $newWaiterId = null;

    public ?int $mergeSourceId = null;

    public ?int $mergeTargetId = null;

    public ?int $splitTableId = null;

    public function startReassign(int $tableId): void
    {
        $this->reassignTableId = $tableId;
        $this->newWaiterId = null;
    }

    public function cancelReassign(): void
    {
        $this->reassignTableId = null;
        $this->newWaiterId = null;
    }

    public function transferTable(): void
    {
        if (! $this->reassignTableId || ! $this->newWaiterId) {
            return;
        }

        $table = Table::findOrFail($this->reassignTableId);
        $newWaiter = User::findOrFail($this->newWaiterId);

        WaiterAssignment::where('table_id', $table->id)
            ->where('status', 'active')
            ->update([
                'status' => 'inactive',
                'unassigned_at' => now(),
            ]);

        WaiterAssignment::create([
            'table_id' => $table->id,
            'waiter_id' => $newWaiter->id,
            'is_primary' => true,
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        TableHistory::create([
            'table_id' => $table->id,
            'waiter_id' => $newWaiter->id,
            'action' => 'transferred',
        ]);

        $this->cancelReassign();

        Notification::make()
            ->title("Mesa {$table->number} reasignada a {$newWaiter->name}")
            ->success()
            ->send();
    }

    public function getAvailableWaiters(): array
    {
        return WaiterShift::with('waiter')
            ->where('status', 'active')
            ->where('is_on_break', false)
            ->get()
            ->map(fn ($s) => ['id' => $s->waiter->id, 'name' => $s->waiter->name])
            ->values()
            ->toArray();
    }

    public function startMerge(int $sourceId): void
    {
        $this->mergeSourceId = $sourceId;
        $this->mergeTargetId = null;
    }

    public function cancelMerge(): void
    {
        $this->mergeSourceId = null;
        $this->mergeTargetId = null;
    }

    public function mergeTables(): void
    {
        if (! $this->mergeSourceId || ! $this->mergeTargetId || $this->mergeSourceId === $this->mergeTargetId) {
            return;
        }

        $source = Table::findOrFail($this->mergeSourceId);
        $target = Table::findOrFail($this->mergeTargetId);

        $source->update(['merged_into_id' => $target->id]);

        WaiterAssignment::where('table_id', $source->id)
            ->where('status', 'active')
            ->update(['status' => 'inactive', 'unassigned_at' => now()]);

        TableHistory::create([
            'table_id' => $source->id,
            'waiter_id' => auth()->id(),
            'action' => 'merged_into',
        ]);

        $this->cancelMerge();

        Notification::make()
            ->title("Mesa {$source->number} fusionada con mesa {$target->number}")
            ->success()
            ->send();
    }

    public function splitTable(int $tableId): void
    {
        $table = Table::findOrFail($tableId);

        Table::where('merged_into_id', $table->id)->each(function (Table $merged) {
            $merged->update(['merged_into_id' => null]);

            TableHistory::create([
                'table_id' => $merged->id,
                'waiter_id' => auth()->id(),
                'action' => 'split',
            ]);
        });

        Notification::make()
            ->title("Mesas separadas de {$table->number}")
            ->success()
            ->send();
    }

    public function getMergeCandidates(int $excludeId): array
    {
        return Table::where('is_active', true)
            ->whereNull('merged_into_id')
            ->where('id', '!=', $excludeId)
            ->get()
            ->map(fn ($t) => ['id' => $t->id, 'number' => $t->number])
            ->values()
            ->toArray();
    }

    private function notifyJefes(ServiceAlert $alert): void
    {
        $jefes = User::role(['super_admin', 'admin'])->get();
        foreach ($jefes as $jefe) {
            $jefe->notify($alert);
        }
    }
}
