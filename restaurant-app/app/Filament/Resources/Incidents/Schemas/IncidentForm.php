<?php

namespace App\Filament\Resources\Incidents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IncidentForm
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
                    ->preload(),
                Select::make('order_id')
                    ->label('Pedido')
                    ->relationship('order', 'id')
                    ->searchable()
                    ->preload(),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'needs_help' => 'Necesita ayuda',
                        'table_abandoned' => 'Mesa abandonada',
                        'spill' => 'Derrame',
                        'unhappy_customer' => 'Cliente molesto',
                        'damaged_table' => 'Mesa dañada',
                        'other' => 'Otro',
                    ])
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'resolved' => 'Resuelto',
                    ])
                    ->default('open')
                    ->required(),
                Select::make('resolved_by')
                    ->label('Resuelto por')
                    ->relationship('resolver', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
