<?php

namespace App\Filament\Resources\Availabilities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AvailabilitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Profissional')
                    ->searchable(),
                TextColumn::make('weekday')
                    ->label('Dia da semana')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Domingo',
                        1 => 'Segunda',
                        2 => 'Terça',
                        3 => 'Quarta',
                        4 => 'Quinta',
                        5 => 'Sexta',
                        6 => 'Sábado',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Início')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Término')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('slot_interval_minutes')
                    ->label('Intervalo (min)')
                    ->numeric()
                    ->sortable(),
            ])
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