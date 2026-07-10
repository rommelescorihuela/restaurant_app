<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CajeraDashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.cajera-dashboard';

    protected static string | UnitEnum | null $navigationGroup = 'Cajera';

    public function getTitle(): string
    {
        return 'Caja';
    }

    public function closeOrder(int $orderId, string $paymentMethod): void
    {
        $order = Order::findOrFail($orderId);

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
        ]);

        Notification::make()
            ->title("Mesa {$order->table->number} — cuenta cerrada")
            ->success()
            ->send();
    }

    public function getOrdersData(): array
    {
        $orders = Order::with([
            'table.zone',
            'items.dish',
            'waiter',
            'customer',
        ])
            ->whereIn('status', ['ready', 'served', 'pending', 'preparing'])
            ->orderByRaw("CASE WHEN status = 'ready' THEN 0 WHEN status = 'served' THEN 1 WHEN status = 'preparing' THEN 2 ELSE 3 END")
            ->orderBy('created_at')
            ->get();

        return $orders->map(fn ($order) => [
            'id' => $order->id,
            'table_number' => $order->table->number,
            'zone' => $order->table->zone?->name ?? '',
            'waiter' => $order->waiter?->name ?? '—',
            'customer' => $order->customer?->name,
            'status' => $order->status,
            'total' => $order->total,
            'items_count' => $order->items->count(),
            'items' => $order->items->map(fn ($item) => [
                'dish' => $item->dish->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'modifiers' => $item->modifiers,
                'notes' => $item->notes,
                'status' => $item->status,
            ])->toArray(),
            'created_at' => $order->created_at->format('H:i'),
            'minutes_ago' => (int) $order->created_at->diffInMinutes(now()),
            'notes' => $order->notes,
            'internal_note' => $order->internal_note,
        ])->toArray();
    }

    public function getSummary(): array
    {
        return [
            'ready' => Order::where('status', 'ready')->count(),
            'served' => Order::where('status', 'served')->count(),
            'total_pending' => Order::whereIn('status', ['ready', 'served'])
                ->sum('total'),
            'today_closed' => Order::where('status', 'paid')
                ->whereDate('paid_at', today())
                ->sum('total'),
        ];
    }
}
