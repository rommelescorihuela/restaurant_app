<?php

namespace App\Filament\Resources\WaiterShifts\Tables;

use App\Filament\Resources\WaiterShifts\WaiterShiftResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class WaiterShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('waiter.name')
                    ->label('Mesonero')
                    ->searchable()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'completed' => 'Completado',
                        'paused' => 'En pausa',
                    ]),
                TextColumn::make('started_at')
                    ->label('Inicio')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ended_at')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_on_break')
                    ->label('Descanso')
                    ->sortable(),
            ])
            ->defaultSort('started_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver corte')
                    ->url(fn ($record) => WaiterShiftResource::getUrl('view', [$record])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
