<?php

namespace App\Filament\App\Resources\WasteRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WasteRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dish.name')
                    ->label('Plato')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cant.')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Motivo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'overcooked' => 'Sobre cocción',
                        'mistake' => 'Error preparación',
                        'returned' => 'Devuelto',
                        'spoiled' => 'En mal estado',
                        'overproduction' => 'Sobreproducción',
                        default => 'Otro',
                    })
                    ->color(fn ($state) => match ($state) {
                        'overcooked', 'spoiled' => 'danger',
                        'mistake', 'returned' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('registeredBy.name')
                    ->label('Registró')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
