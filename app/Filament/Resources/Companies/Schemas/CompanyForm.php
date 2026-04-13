<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli Principali')
                    ->description("Informazioni base dell'azienda cliente.")
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome Azienda')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)  // Ascolta i cambiamenti quando l'utente esce dal campo
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                // Se stiamo creando una nuova azienda, genera lo slug in automatico!
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug (URL della pagina)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText("Questo sarà l'indirizzo web. Es: tuoservizio.it/segnala/acme-corp"),
                    ])
                    ->columns(2),
                Section::make('Personalizzazione White-Label')
                    ->description("Adatta l'interfaccia ai colori e al brand del cliente.")
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo Aziendale')
                            ->image()
                            ->directory('company-logos')  // Salva nella cartella storage/app/public/company-logos
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        ColorPicker::make('brand_color')
                            ->label('Colore Principale (Brand Color)')
                            ->default('#1d4ed8'),
                    ]),
                Section::make('Sicurezza e Accesso')
                    ->schema([
                        TextInput::make('shared_passcode')
                            ->label('Codice Aziendale Condiviso')
                            ->helperText('I dipendenti dovranno inserire questo codice per sbloccare il form di segnalazione.')
                            ->maxLength(255)
                            ->default(fn() => strtoupper(Str::random(8)))  // Genera un codice casuale di 8 lettere/numeri
                            ->revealable(),  // Permette di nascondere/mostrare il testo
                    ]),
            ]);
    }
}
