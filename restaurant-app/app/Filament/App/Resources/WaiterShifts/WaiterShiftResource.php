<?php

namespace App\Filament\App\Resources\WaiterShifts;

use App\Filament\App\Resources\WaiterShifts\Pages\CreateWaiterShift;
use App\Filament\App\Resources\WaiterShifts\Pages\EditWaiterShift;
use App\Filament\App\Resources\WaiterShifts\Pages\ListWaiterShifts;
use App\Filament\App\Resources\WaiterShifts\Pages\ViewWaiterShift;
use App\Filament\App\Resources\WaiterShifts\Schemas\WaiterShiftForm;
use App\Filament\App\Resources\WaiterShifts\Tables\WaiterShiftsTable;
use App\Models\WaiterShift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WaiterShiftResource extends Resource
{
    protected static ?string $model = WaiterShift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string | UnitEnum | null $navigationGroup = 'Mesoneros';

    public static function form(Schema $schema): Schema
    {
        return WaiterShiftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaiterShiftsTable::configure($table);
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
            'index' => ListWaiterShifts::route('/'),
            'create' => CreateWaiterShift::route('/create'),
            'view' => ViewWaiterShift::route('/{record}'),
            'edit' => EditWaiterShift::route('/{record}/edit'),
        ];
    }
}
