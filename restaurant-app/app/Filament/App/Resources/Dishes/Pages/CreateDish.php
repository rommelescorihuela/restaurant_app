<?php

namespace App\Filament\App\Resources\Dishes\Pages;

use App\Filament\App\Resources\Dishes\DishResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDish extends CreateRecord
{
    protected static string $resource = DishResource::class;
}
