<?php

namespace App\Filament\App\Resources\Tables\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('zone_id')
                    ->label('Zona')
                    ->relationship('zone', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('number')
                    ->label('Número')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true),
                TextInput::make('capacity')
                    ->label('Capacidad')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('location')
                    ->label('Ubicación')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
