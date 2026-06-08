<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->label('Profissional')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nome do serviço')
                    ->required(),
                Select::make('duration_minutes')
                    ->label('Duração')
                    ->options([
                        30  => '30 minutos',
                        45  => '45 minutos',
                        60  => '1 hora',
                        90  => '1h30',
                        120 => '2 horas',
                        150 => '2h30',
                        180 => '3 horas',
                    ])
                    ->required(),
                TextInput::make('price')
                    ->label('Preço')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Toggle::make('active')
                    ->label('Ativo')
                    ->required(),
            ]);
    }
}