<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dati Anagrafici')
                    ->description("Crea l'account per il responsabile delle segnalazioni (HR/Compliance).")
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome e Cognome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Indirizzo Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')  // Obbligatoria solo in creazione
                            ->rule(Password::default())
                            ->helperText("Lascia vuoto se stai modificando l'utente e non vuoi cambiare la password."),
                    ])
                    ->columns(2),
                Section::make('Assegnazione Azienda (Multi-Tenancy)')
                    ->description('Collega questo utente a una o più aziende. Vedrà solo le segnalazioni di queste aziende.')
                    ->schema([
                        Select::make('companies')
                            ->label('Aziende Gestite')
                            ->relationship('companies', 'name')
                            ->multiple()  // Un utente potrebbe gestire più aziende
                            ->preload()  // Carica la lista delle aziende in anticipo
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
