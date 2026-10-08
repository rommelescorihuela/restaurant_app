<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaiterShift extends Model
{
    use BelongsToRestaurant, HasFactory;

    protected $fillable = [
        'restaurant_id',
        'waiter_id', 'started_at', 'ended_at',
        'is_on_break', 'break_started_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'is_on_break' => 'boolean',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'break_started_at' => 'datetime',
        ];
    }

    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }
}
