<?php

namespace App\Filament\Resources\WaiterShifts\Pages;

use App\Filament\Resources\WaiterShifts\WaiterShiftResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWaiterShifts extends ListRecords
{
    protected static string $resource = WaiterShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
