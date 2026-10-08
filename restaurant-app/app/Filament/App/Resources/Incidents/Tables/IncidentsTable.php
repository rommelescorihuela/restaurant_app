<?php

namespace App\Filament\App\Resources\Incidents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('table.number')
                    ->label('Mesa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('waiter.name')
                    ->label('Mesonero')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'needs_help' => 'purple',
                        'table_abandoned' => 'danger',
                        'spill' => 'warning',
                        'unhappy_customer' => 'danger',
                        'damaged_table' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(60),
                SelectColumn::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'resolved' => 'Resuelto',
                    ]),
                TextColumn::make('created_at')
                    ->label('Creado')
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
