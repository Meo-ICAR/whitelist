<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_token')
                    ->label('PIN')
                    ->searchable()
                    ->fontFamily('mono')
                    ->weight('bold'),
                TextColumn::make('title')
                    ->label('Oggetto')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'danger',  // Rosso per le nuove (richiedono attenzione)
                        'in_progress' => 'warning',  // Giallo per quelle in lavorazione
                        'closed' => 'success',  // Verde per quelle chiuse
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'Nuova',
                        'in_progress' => 'In Lavorazione',
                        'closed' => 'Chiusa',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Ricevuta il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtra per Stato')
                    ->options([
                        'new' => 'Nuove',
                        'in_progress' => 'In Lavorazione',
                        'closed' => 'Chiuse',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Apri e Gestisci'),  // Cambiamo il testo del bottone, "Edit" non è il termine giusto qui
            ])
            ->bulkActions([
                // Rimuoviamo il DeleteBulkAction. Le segnalazioni NON vanno mai cancellate in massa per legge.
            ]);
    }
}
