<?php

namespace App\Filament\App\Resources\Zones\Pages;

use App\Filament\App\Resources\Zones\ZoneResource;
use Filament\Resources\Pages\ListRecords;

class ListZones extends ListRecords
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
