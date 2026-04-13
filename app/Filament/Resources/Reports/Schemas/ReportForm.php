<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli della Segnalazione')
                    ->description('Il contenuto è crittografato nel database e visibile solo in questa schermata.')
                    ->schema([
                        TextInput::make('tracking_token')
                            ->label('PIN Pratica')
                            ->disabled()  // Disabilitato: non può essere modificato
                            ->columnSpan(1),
                        TextInput::make('created_at')
                            ->label('Data e Ora di Invio')
                            ->formatStateUsing(fn($state) => $state ? $state->format('d/m/Y H:i') : '')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('title')
                            ->label('Oggetto')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Descrizione dei Fatti')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                        // Mostra gli eventuali allegati caricati dal segnalante
                        SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('Prove / Allegati')
                            ->collection('evidence')  // Allineato con registerMediaCollections() nel modello Report
                            ->disabled()  // Non può aggiungere o togliere file da qui
                            ->downloadable()  // Ma può scaricarli per visionarli
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Gestione Pratica')
                    ->schema([
                        Select::make('status')
                            ->label('Stato Segnalazione')
                            ->options([
                                'new' => 'Nuova (Da prendere in carico)',
                                'in_progress' => 'In Lavorazione / Indagine in corso',
                                'closed' => 'Chiusa / Archiviata',
                            ])
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }
}
