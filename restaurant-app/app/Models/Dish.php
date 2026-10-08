<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dish extends Model implements HasMedia
{
    use BelongsToRestaurant, HasFactory, InteractsWithMedia;

    protected $fillable = ['restaurant_id', 'category_id', 'name', 'description', 'price', 'is_available'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
