<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Table;
use App\Models\TableHistory;
use App\Models\WaiterAssignment;

class OrderObserver
{
    public function created(Order $order): void
    {
        $table = $order->table;

        Table::where('id', $order->table_id)
            ->where('alerted_abandoned', true)
            ->update(['alerted_abandoned' => false]);

        $hasActiveAssignment = WaiterAssignment::where('table_id', $order->table_id)
            ->where('status', 'active')
            ->exists();

        if (! $hasActiveAssignment) {
            WaiterAssignment::create([
                'table_id' => $order->table_id,
                'waiter_id' => $order->waiter_id,
                'is_primary' => true,
                'status' => 'active',
                'assigned_at' => now(),
            ]);

            TableHistory::create([
                'table_id' => $order->table_id,
                'waiter_id' => $order->waiter_id,
                'order_id' => $order->id,
                'action' => 'auto_assigned',
            ]);
        }
    }
}
