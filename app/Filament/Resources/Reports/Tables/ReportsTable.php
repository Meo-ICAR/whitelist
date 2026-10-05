<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enums\ReportStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                    ->badge(),
                IconColumn::make('acknowledgement_overdue')
                    ->label('Avviso scaduto')
                    ->boolean()
                    ->getStateUsing(fn ($record) => is_null($record->acknowledged_at) && $record->acknowledgement_due_at?->isPast())
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check')
                    ->falseColor('gray'),
                IconColumn::make('feedback_overdue')
                    ->label('Riscontro scaduto')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->status !== ReportStatus::Closed && $record->feedback_due_at?->isPast())
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check')
                    ->falseColor('gray'),
                IconColumn::make('meeting_requested_at')
                    ->label('Incontro richiesto')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Ricevuta il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtra per Stato')
                    ->options(ReportStatus::class),
                Filter::make('deadlines_overdue')
                    ->label('Scadenze superate')
                    ->query(fn ($query) => $query->where(function ($query) {
                        $query->acknowledgementDeadlineWithin(0)
                            ->orWhere(fn ($query) => $query->feedbackDeadlineWithin(0));
                    })),
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
