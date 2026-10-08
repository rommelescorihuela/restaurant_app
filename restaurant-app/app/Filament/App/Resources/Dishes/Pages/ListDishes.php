<?php

namespace App\Filament\App\Resources\Dishes\Pages;

use App\Filament\App\Resources\Dishes\DishResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDishes extends ListRecords
{
    protected static string $resource = DishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
