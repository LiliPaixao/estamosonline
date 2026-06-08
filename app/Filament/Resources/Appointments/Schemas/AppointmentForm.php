<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('tenant_id')
                        ->relationship('tenant', 'name')
                        ->label('Profissional')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('service_id')
                        ->relationship('service', 'name')
                        ->label('Serviço')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('client_name')
                        ->label('Nome do cliente')
                        ->required(),
                    TextInput::make('client_whatsapp')
                        ->label('WhatsApp')
                        ->tel()
                        ->required(),
                ]),
                Grid::make(2)->schema([
                    DateTimePicker::make('scheduled_at')
                        ->label('Início')
                        ->seconds(false)
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Término')
                        ->seconds(false)
                        ->required(),
                ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}