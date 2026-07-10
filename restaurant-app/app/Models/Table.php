<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'capacity', 'location', 'is_active', 'zone_id',
        'help_requested_at', 'alerted_abandoned', 'merged_into_id',
    ];

    protected function casts(): array
    {
        return [
            'help_requested_at' => 'datetime',
            'alerted_abandoned' => 'boolean',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function mergedInto(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'merged_into_id');
    }

    public function mergedTables(): HasMany
    {
        return $this->hasMany(Table::class, 'merged_into_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WaiterAssignment::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TableHistory::class);
    }

    public function allOrders(): HasMany
    {
        $query = $this->orders();

        if ($this->relationLoaded('mergedTables') && $this->mergedTables->isNotEmpty()) {
            $mergedIds = $this->mergedTables->pluck('id');
            $query->orWhereIn('table_id', $mergedIds);
        }

        return $query;
    }
}
