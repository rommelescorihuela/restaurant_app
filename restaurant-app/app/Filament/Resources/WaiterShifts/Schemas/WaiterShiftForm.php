<?php

namespace App\Filament\Resources\WaiterShifts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WaiterShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('waiter_id')
                    ->label('Mesonero')
                    ->relationship('waiter', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'completed' => 'Completado',
                        'paused' => 'En pausa',
                    ])
                    ->default('active')
                    ->required(),
                DateTimePicker::make('started_at')
                    ->label('Inicio'),
                DateTimePicker::make('ended_at')
                    ->label('Fin'),
                Toggle::make('is_on_break')
                    ->label('En descanso')
                    ->default(false),
                DateTimePicker::make('break_started_at')
                    ->label('Descanso desde'),
            ]);
    }
}
