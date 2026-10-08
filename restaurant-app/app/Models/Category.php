<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use BelongsToRestaurant, HasFactory, InteractsWithMedia;

    protected $fillable = ['restaurant_id', 'name', 'slug', 'description', 'is_active'];

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }
}
