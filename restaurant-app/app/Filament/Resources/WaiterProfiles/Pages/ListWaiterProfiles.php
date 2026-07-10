<?php

namespace App\Filament\Resources\WaiterProfiles\Pages;

use App\Filament\Resources\WaiterProfiles\WaiterProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWaiterProfiles extends ListRecords
{
    protected static string $resource = WaiterProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
