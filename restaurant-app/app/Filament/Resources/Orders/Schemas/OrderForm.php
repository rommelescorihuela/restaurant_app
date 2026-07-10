<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('table_id')
                    ->label('Mesa')
                    ->relationship('table', 'number')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('waiter_id')
                    ->label('Mesonero')
                    ->relationship('waiter', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'preparing' => 'Preparando',
                        'ready' => 'Listo',
                        'served' => 'Servido',
                        'cancelled' => 'Cancelado',
                    ])
                    ->default('pending')
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
                Textarea::make('internal_note')
                    ->label('Nota interna (cocina)')
                    ->columnSpanFull(),
                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->default(0),
                Repeater::make('items')
                    ->label('Items')
                    ->relationship()
                    ->schema([
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
                            ->minValue(1),
                        TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->required(),
                        Textarea::make('modifiers')
                            ->label('Modificadores'),
                        Textarea::make('notes')
                            ->label('Notas'),
                    ])
                    ->columns(2),
            ]);
    }
}
