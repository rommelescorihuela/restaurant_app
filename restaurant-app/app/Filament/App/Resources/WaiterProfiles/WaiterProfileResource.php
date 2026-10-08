<?php

namespace App\Filament\App\Resources\WaiterProfiles;

use App\Filament\App\Resources\WaiterProfiles\Pages\CreateWaiterProfile;
use App\Filament\App\Resources\WaiterProfiles\Pages\EditWaiterProfile;
use App\Filament\App\Resources\WaiterProfiles\Pages\ListWaiterProfiles;
use App\Filament\App\Resources\WaiterProfiles\Schemas\WaiterProfileForm;
use App\Filament\App\Resources\WaiterProfiles\Tables\WaiterProfilesTable;
use App\Models\WaiterProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WaiterProfileResource extends Resource
{
    protected static ?string $model = WaiterProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string | UnitEnum | null $navigationGroup = 'Mesoneros';

    public static function form(Schema $schema): Schema
    {
        return WaiterProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaiterProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWaiterProfiles::route('/'),
            'create' => CreateWaiterProfile::route('/create'),
            'edit' => EditWaiterProfile::route('/{record}/edit'),
        ];
    }
}
