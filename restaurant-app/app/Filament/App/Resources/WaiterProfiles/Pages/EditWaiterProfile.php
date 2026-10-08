<?php

namespace App\Filament\App\Resources\WaiterProfiles\Pages;

use App\Filament\App\Resources\WaiterProfiles\WaiterProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaiterProfile extends EditRecord
{
    protected static string $resource = WaiterProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
