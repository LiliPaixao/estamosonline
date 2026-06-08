<?php

namespace App\Filament\Resources\Availabilities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AvailabilityForm
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
                Select::make('weekday')
                    ->label('Dia da semana')
                    ->options([
                        0 => 'Domingo',
                        1 => 'Segunda',
                        2 => 'Terça',
                        3 => 'Quarta',
                        4 => 'Quinta',
                        5 => 'Sexta',
                        6 => 'Sábado',
                    ])
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Horário de início')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Horário de término')
                    ->seconds(false)
                    ->required(),
                Select::make('slot_interval_minutes')
                    ->label('Intervalo entre horários')
                    ->options([
                        30  => '30 minutos',
                        60  => '1 hora',
                        90  => '1h30',
                        120 => '2 horas',
                    ])
                    ->default(60)
                    ->required(),
            ]);
    }
}