<?php

namespace App\Http\Controllers;

use App\Models\Order;

class CocinaTvController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'table.zone',
            'items.dish.category',
            'waiter',
        ])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderByRaw("CASE WHEN priority = 'vip' THEN 0 WHEN priority = 'urgent' THEN 1 ELSE 2 END")
            ->orderBy('created_at')
            ->get()
            ->map(fn ($order) => [
                'table_number' => $order->table->number,
                'zone' => $order->table->zone?->name ?? '',
                'priority' => $order->priority,
                'status' => $order->status,
                'created_at' => $order->created_at->format('H:i'),
                'minutes_ago' => (int) $order->created_at->diffInMinutes(now()),
                'items' => $order->items->map(fn ($item) => [
                    'dish' => $item->dish->name,
                    'quantity' => $item->quantity,
                    'modifiers' => $item->modifiers,
                    'notes' => $item->notes,
                    'status' => $item->status,
                    'kitchen_note' => $item->kitchen_note,
                    'return_reason' => $item->return_reason,
                    'minutes_since_created' => (int) $item->created_at->diffInMinutes(now()),
                    'photo' => $item->dish->getFirstMediaUrl('dishes', 'thumb') ?: null,
                ])->toArray(),
            ]);

        $summary = [
            'pending' => $orders->sum(fn ($o) => count(array_filter($o['items'], fn ($i) => $i['status'] === 'pending'))),
            'preparing' => $orders->sum(fn ($o) => count(array_filter($o['items'], fn ($i) => $i['status'] === 'preparing'))),
            'ready' => $orders->sum(fn ($o) => count(array_filter($o['items'], fn ($i) => $i['status'] === 'ready'))),
        ];

        return view('cocina-tv', compact('orders', 'summary'));
    }
}
