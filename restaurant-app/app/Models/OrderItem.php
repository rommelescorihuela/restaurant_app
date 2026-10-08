<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use BelongsToRestaurant, HasFactory;

    protected $fillable = [
        'restaurant_id',
        'order_id', 'dish_id', 'quantity',
        'modifiers', 'status', 'notes', 'price',
        'started_at', 'prepared_at', 'cancelled_at', 'cancel_reason',
        'kitchen_note', 'return_reason', 'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'prepared_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopePreparing($q)
    {
        return $q->where('status', 'preparing');
    }

    public function minutesSinceCreation(): int
    {
        return (int) $this->created_at->diffInMinutes(now());
    }

    public function minutesInPreparation(): ?int
    {
        if (! $this->started_at) return null;
        $end = $this->prepared_at ?? now();
        return (int) $this->started_at->diffInMinutes($end);
    }

    public function returnToKitchen(string $reason): void
    {
        $this->update([
            'status' => 'preparing',
            'started_at' => now(),
            'return_reason' => $reason,
            'returned_at' => now(),
        ]);
    }
}
