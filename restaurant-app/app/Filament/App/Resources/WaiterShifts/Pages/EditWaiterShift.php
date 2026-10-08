<?php

namespace App\Filament\App\Resources\WaiterShifts\Pages;

use App\Filament\App\Resources\WaiterShifts\WaiterShiftResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaiterShift extends EditRecord
{
    protected static string $resource = WaiterShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
