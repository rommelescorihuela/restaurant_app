<?php

namespace App\Filament\Resources\Subscriptions;

use App\Enums\Plan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Suscripciones';

    protected static ?string $modelLabel = 'Suscripción';

    protected static ?string $pluralModelLabel = 'Suscripciones';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('restaurant_id')
                    ->label('Restaurante')
                    ->relationship('restaurant', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('plan')
                    ->label('Plan')
                    ->options(collect(Plan::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])->all())
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(collect(SubscriptionStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all())
                    ->required(),
                Forms\Components\DateTimePicker::make('trial_ends_at')
                    ->label('Fin de prueba'),
                Forms\Components\DatePicker::make('current_period_start')
                    ->label('Inicio del periodo'),
                Forms\Components\DatePicker::make('current_period_end')
                    ->label('Fin del periodo'),
                Forms\Components\TextInput::make('seats_limit')
                    ->label('Límite de usuarios')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),
                Tables\Columns\TextColumn::make('current_period_end')
                    ->label('Vence')
                    ->date()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
