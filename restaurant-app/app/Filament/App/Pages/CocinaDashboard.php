<?php

namespace App\Filament\App\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\WasteRecord;
use App\Notifications\KitchenAlert;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CocinaDashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.cocina-dashboard';

    protected static string | UnitEnum | null $navigationGroup = 'Cocina';

    public ?string $viewMode = 'grouped';

    public ?int $priorityOrderId = null;

    public ?string $newPriority = null;

    public function getTitle(): string
    {
        return 'Cocina';
    }

    public function startPreparing(int $itemId): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->update([
            'status' => 'preparing',
            'started_at' => now(),
        ]);

        $this->markOrderPreparing($item->order_id);
    }

    public function markReady(int $itemId): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->update([
            'status' => 'ready',
            'prepared_at' => now(),
        ]);

        $this->checkOrderReady($item->order_id);
    }

    public function cancelItem(int $itemId): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $this->notifyKitchen('cancelled', "{$item->dish->name} cancelado en mesa {$item->order->table->number}", $item);

        Notification::make()
            ->title("{$item->dish->name} cancelado")
            ->warning()
            ->send();
    }

    public function returnItem(int $itemId): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->returnToKitchen('Devuelto por el mesonero');

        $order = $item->order;
        if ($order->status === 'ready') {
            $order->update(['status' => 'preparing']);
        }

        Notification::make()
            ->title("{$item->dish->name} devuelto a preparación")
            ->warning()
            ->send();
    }

    public function returnItemWithReason(int $itemId, string $reason): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->returnToKitchen($reason ?: 'Devuelto por el mesonero');

        $order = $item->order;
        if ($order->status === 'ready') {
            $order->update(['status' => 'preparing']);
        }

        $this->notifyKitchen('returned', "{$item->dish->name} devuelto a cocina: {$reason}", $item);

        Notification::make()
            ->title("{$item->dish->name} devuelto: {$reason}")
            ->warning()
            ->send();
    }

    public function saveKitchenNote(int $itemId, ?string $note): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->update(['kitchen_note' => $note]);

        Notification::make()
            ->title('Nota guardada')
            ->success()
            ->send();
    }

    public function setPriority(int $orderId, string $priority): void
    {
        Order::where('id', $orderId)->update(['priority' => $priority]);

        Notification::make()
            ->title('Prioridad actualizada')
            ->success()
            ->send();
    }

    private function markOrderPreparing(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'preparing']);
        }
    }

    private function checkOrderReady(int $orderId): void
    {
        $order = Order::with('items')->find($orderId);
        if (! $order) return;

        $allReadyOrCancelled = $order->items->every(fn ($i) => in_array($i->status, ['ready', 'served', 'cancelled']));
        if ($allReadyOrCancelled && $order->status !== 'ready') {
            $order->update(['status' => 'ready']);
        }
    }

    public function getOrdersData(): array
    {
        $orders = Order::with([
            'table.zone',
            'items.dish.category',
            'waiter',
        ])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderByRaw("CASE WHEN priority = 'vip' THEN 0 WHEN priority = 'urgent' THEN 1 ELSE 2 END")
            ->orderBy('created_at')
            ->get();

        return $orders->map(fn ($order) => [
            'id' => $order->id,
            'table_number' => $order->table->number,
            'zone' => $order->table->zone?->name ?? '—',
            'waiter' => $order->waiter?->name ?? '—',
            'priority' => $order->priority,
            'status' => $order->status,
            'total_items' => $order->items->count(),
            'pending_count' => $order->items->where('status', 'pending')->count(),
            'preparing_count' => $order->items->where('status', 'preparing')->count(),
            'ready_count' => $order->items->where('status', 'ready')->count(),
            'cancelled_count' => $order->items->where('status', 'cancelled')->count(),
            'created_at' => $order->created_at->format('H:i'),
            'created_at_diff' => $order->created_at->diffForHumans(),
            'minutes_ago' => (int) $order->created_at->diffInMinutes(now()),
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'dish' => $item->dish->name,
                'quantity' => $item->quantity,
                'modifiers' => $item->modifiers,
                'notes' => $item->notes,
                'status' => $item->status,
                'minutes_since_created' => (int) $item->created_at->diffInMinutes(now()),
                'minutes_preparing' => $item->started_at
                    ? (int) $item->started_at->diffInMinutes($item->prepared_at ?? now())
                    : null,
                'kitchen_note' => $item->kitchen_note,
                'return_reason' => $item->return_reason,
                'returned_at' => $item->returned_at?->diffForHumans(),
                'photo' => $item->dish->getFirstMediaUrl('dishes', 'thumb') ?: null,
            ])->toArray(),
        ])->toArray();
    }

    public function getStationData(): array
    {
        $items = OrderItem::with([
            'dish.category',
            'order.table',
        ])
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['pending', 'preparing']))
            ->whereIn('status', ['pending', 'preparing'])
            ->get()
            ->groupBy(fn ($item) => $item->dish->category?->name ?? 'Sin estación')
            ->map(fn ($group, $station) => [
                'station' => $station,
                'count' => $group->count(),
                'items' => $group->map(fn ($item) => [
                    'id' => $item->id,
                    'dish' => $item->dish->name,
                    'quantity' => $item->quantity,
                    'modifiers' => $item->modifiers,
                    'notes' => $item->notes,
                    'status' => $item->status,
                    'table_number' => $item->order->table->number,
                    'minutes_since_created' => (int) $item->created_at->diffInMinutes(now()),
                    'minutes_preparing' => $item->started_at
                        ? (int) $item->started_at->diffInMinutes($item->prepared_at ?? now())
                        : null,
                    'kitchen_note' => $item->kitchen_note,
                    'return_reason' => $item->return_reason,
                    'photo' => $item->dish->getFirstMediaUrl('dishes', 'thumb') ?: null,
                ])->toArray(),
            ])
            ->values()
            ->toArray();
    }

    public function getSummary(): array
    {
        return [
            'pending' => OrderItem::where('status', 'pending')->count(),
            'preparing' => OrderItem::where('status', 'preparing')->count(),
            'ready' => OrderItem::where('status', 'ready')->count(),
            'orders_pending' => Order::whereIn('status', ['pending', 'preparing'])->count(),
            'overdue' => OrderItem::where('status', 'preparing')
                ->where('started_at', '<', now()->subMinutes(15))
                ->count(),
        ];
    }

    public function getBatchData(): array
    {
        $items = OrderItem::with([
            'dish',
            'order.table',
        ])
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['pending', 'preparing']))
            ->whereIn('status', ['pending', 'preparing'])
            ->get()
            ->groupBy(fn ($item) => $item->dish->name)
            ->map(fn ($group, $dishName) => [
                'dish' => $dishName,
                'total_quantity' => $group->sum('quantity'),
                'item_ids' => $group->pluck('id')->toArray(),
                'tables' => $group->pluck('order.table.number')->unique()->sort()->values()->toArray(),
                'items' => $group->map(fn ($item) => [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'table_number' => $item->order->table->number,
                    'status' => $item->status,
                    'modifiers' => $item->modifiers,
                    'notes' => $item->notes,
                    'minutes_since_created' => (int) $item->created_at->diffInMinutes(now()),
                    'photo' => $item->dish->getFirstMediaUrl('dishes', 'thumb') ?: null,
                ])->toArray(),
            ])
            ->sortByDesc(fn ($batch) => $batch['total_quantity'])
            ->values()
            ->toArray();
    }

    public function markBatchReady(array $itemIds): void
    {
        OrderItem::whereIn('id', $itemIds)
            ->whereIn('status', ['pending', 'preparing'])
            ->update([
                'status' => 'ready',
                'prepared_at' => now(),
            ]);

        $orderIds = OrderItem::whereIn('id', $itemIds)->distinct()->pluck('order_id');
        foreach ($orderIds as $orderId) {
            $this->checkOrderReady($orderId);
        }

        Notification::make()
            ->title('Lote preparado')
            ->success()
            ->send();
    }

    public function registerWaste(int $itemId, string $reason): void
    {
        $item = OrderItem::with('dish')->findOrFail($itemId);

        WasteRecord::create([
            'dish_id' => $item->dish_id,
            'quantity' => $item->quantity,
            'reason' => $reason,
            'notes' => "Desde cocina — mesa {$item->order->table->number}",
            'registered_by' => auth()->id(),
        ]);

        if (in_array($item->status, ['pending', 'preparing'])) {
            $item->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason' => "Merma: {$reason}",
            ]);
            $this->checkOrderReady($item->order_id);
        }

        $this->notifyKitchen('waste', "Merma registrada: {$item->dish->name} x{$item->quantity}", $item);

        Notification::make()
            ->title('Merma registrada')
            ->success()
            ->send();
    }

    private function notifyKitchen(string $type, string $message, ?OrderItem $item = null): void
    {
        $chiefs = User::role(['super_admin', 'admin'])->get();
        foreach ($chiefs as $chief) {
            $chief->notify(new KitchenAlert($type, $message, $item));
        }
    }
}
