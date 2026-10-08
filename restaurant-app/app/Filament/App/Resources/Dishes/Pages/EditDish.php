<?php

namespace App\Filament\App\Resources\Dishes\Pages;

use App\Filament\App\Resources\Dishes\DishResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDish extends EditRecord
{
    protected static string $resource = DishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
