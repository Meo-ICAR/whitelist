<?php

namespace App\Filament\Resources\Reports\RelationManagers;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogRelationManager extends RelationManager
{
    protected static string $relationship = 'activitiesAsSubject';

    protected static ?string $relatedResource = ReportResource::class;

    protected static ?string $title = 'Registro Attività';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data e Ora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Effettuata da')
                    ->default('Segnalante (anonimo) / Sistema'),
                TextColumn::make('description')
                    ->label('Evento'),
                TextColumn::make('attribute_changes')
                    ->label('Dettaglio')
                    ->formatStateUsing(function ($record) {
                        $attributes = $record->attribute_changes['attributes'] ?? null;

                        if (! $attributes) {
                            return '-';
                        }

                        return collect($attributes)
                            ->map(fn ($value, $key) => "{$key}: {$value}")
                            ->implode(', ');
                    }),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
