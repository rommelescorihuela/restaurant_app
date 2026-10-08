<?php

namespace App\Filament\App\Resources\Reservations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('table_id')
                    ->label('Mesa')
                    ->relationship('table', 'number')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                DateTimePicker::make('reservation_date')
                    ->label('Fecha de Reserva')
                    ->required()
                    ->native(false),
                TextInput::make('guest_count')
                    ->label('Número de Comensales')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        'completed' => 'Completada',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
