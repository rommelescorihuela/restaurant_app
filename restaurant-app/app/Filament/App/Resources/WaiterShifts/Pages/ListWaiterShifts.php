<?php

namespace App\Filament\App\Resources\WaiterShifts\Pages;

use App\Filament\App\Resources\WaiterShifts\WaiterShiftResource;
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
