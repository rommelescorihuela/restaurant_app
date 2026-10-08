<?php

namespace App\Filament\App\Resources\WaiterShifts\Pages;

use App\Filament\App\Resources\WaiterShifts\WaiterShiftResource;
use App\Models\Order;
use App\Models\WaiterAssignment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewWaiterShift extends ViewRecord
{
    protected static string $resource = WaiterShiftResource::class;

    protected string $view = 'filament.resources.waiter-shifts.pages.view-waiter-shift';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('closeShift')
                ->label('Cerrar turno')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->record->status === 'active')
                ->action(function () {
                    $this->record->update([
                        'status' => 'completed',
                        'ended_at' => now(),
                        'is_on_break' => false,
                    ]);

                    WaiterAssignment::where('waiter_id', $this->record->waiter_id)
                        ->where('status', 'active')
                        ->update(['status' => 'inactive', 'unassigned_at' => now()]);

                    Notification::make()
                        ->title("Turno de {$this->record->waiter->name} cerrado")
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('view', [$this->record]));
                }),
            DeleteAction::make(),
        ];
    }

    public function getCorteData(): array
    {
        $shift = $this->record;
        $orders = Order::where('waiter_id', $shift->waiter_id)
            ->where('created_at', '>=', $shift->started_at)
            ->when($shift->ended_at, fn ($q) => $q->where('created_at', '<=', $shift->ended_at))
            ->get();

        $totalSales = $orders->sum('total');
        $orderCount = $orders->count();
        $tablesServed = $orders->pluck('table_id')->unique()->count();
        $avgPerOrder = $orderCount > 0 ? $totalSales / $orderCount : 0;
        $avgPerTable = $tablesServed > 0 ? $totalSales / $tablesServed : 0;

        return [
            'total_sales' => $totalSales,
            'order_count' => $orderCount,
            'tables_served' => $tablesServed,
            'avg_per_order' => $avgPerOrder,
            'avg_per_table' => $avgPerTable,
            'orders' => $orders,
            'duration_minutes' => $shift->started_at->diffInMinutes($shift->ended_at ?? now()),
        ];
    }
}
