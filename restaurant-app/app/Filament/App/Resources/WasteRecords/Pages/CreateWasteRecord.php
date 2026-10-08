<?php

namespace App\Filament\App\Resources\WasteRecords\Pages;

use App\Filament\App\Resources\WasteRecords\WasteRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWasteRecord extends CreateRecord
{
    protected static string $resource = WasteRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registered_by'] = auth()->id();
        return $data;
    }
}
