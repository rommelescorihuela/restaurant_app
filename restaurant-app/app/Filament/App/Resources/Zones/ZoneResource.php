<?php

namespace App\Filament\App\Resources\Zones;

use App\Filament\App\Resources\Zones\Pages\CreateZone;
use App\Filament\App\Resources\Zones\Pages\EditZone;
use App\Filament\App\Resources\Zones\Pages\ListZones;
use App\Filament\App\Resources\Zones\Schemas\ZoneForm;
use App\Filament\App\Resources\Zones\Tables\ZonesTable;
use App\Models\Zone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ZoneResource extends Resource
{
    protected static ?string $model = Zone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string | UnitEnum | null $navigationGroup = 'Mesoneros';

    public static function form(Schema $schema): Schema
    {
        return ZoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZonesTable::configure($table);
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
            'index' => ListZones::route('/'),
            'create' => CreateZone::route('/create'),
            'edit' => EditZone::route('/{record}/edit'),
        ];
    }
}
