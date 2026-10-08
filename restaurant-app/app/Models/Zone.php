<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use BelongsToRestaurant, HasFactory;

    protected $fillable = ['restaurant_id', 'name', 'description', 'is_active'];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
