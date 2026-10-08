<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use BelongsToRestaurant, HasFactory;

    protected $fillable = ['restaurant_id', 'name', 'email', 'phone', 'notes'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
