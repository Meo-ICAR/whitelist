<?php

namespace App\Filament\Resources\Reports\RelationManagers;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $relatedResource = ReportResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('body')
                ->label('Messaggio')
                ->required()
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->defaultSort('created_at', 'desc')  // Mostra i messaggi più recenti in alto
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data e Ora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn($record) => $record->created_at->diffForHumans()),  // Aggiunge la scritta "2 ore fa", "Ieri", ecc.
                TextColumn::make('is_from_reporter')
                    ->label('Mittente')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'danger' : 'success')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Segnalante (Anonimo)' : 'Tu (Gestore)'),
                TextColumn::make('body')
                    ->label('Testo del Messaggio')
                    ->wrap()  // Imprescindibile: permette al testo lungo di andare a capo senza rompere la tabella
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Rispondi al Segnalante')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    // SICUREZZA: Forziamo il fatto che questo messaggio arriva dal Gestore e non dal Segnalante
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['is_from_reporter'] = false;
                        return $data;
                    })
                    // Opzionale: un alert per ricordare la riservatezza
                    ->modalDescription("Attenzione: mantieni un tono professionale e non inserire dati che possano identificare altre persone se non strettamente necessario ai fini dell'indagine."),
            ])
            ->actions([
                // INTEGRITÀ LEGALE: Non mettiamo né EditAction né DeleteAction.
                // I messaggi inviati non possono essere modificati o cancellati per garantire l'audit trail.
            ])
            ->bulkActions([
                // Nessuna azione di massa consentita
            ]);
    }
}
