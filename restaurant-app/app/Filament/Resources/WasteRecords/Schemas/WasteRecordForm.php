<?php

namespace App\Filament\Resources\WasteRecords\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WasteRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dish_id')
                    ->label('Plato')
                    ->relationship('dish', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),
                Select::make('reason')
                    ->label('Motivo')
                    ->options([
                        'overcooked' => 'Sobre cocción / quemado',
                        'mistake' => 'Error de preparación',
                        'returned' => 'Devuelto por cliente',
                        'spoiled' => 'Ingrediente en mal estado',
                        'overproduction' => 'Sobreproducción',
                        'other' => 'Otro',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }
}
