<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CorrectiveMeasureTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nome del Modello')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('scope')
                    ->label('Ambito')
                    ->badge()
                    ->getStateUsing(fn ($record) => is_null($record->company_id) ? 'Globale' : 'Aziendale')
                    ->color(fn (string $state) => $state === 'Globale' ? 'info' : 'gray'),
                TextColumn::make('content')
                    ->label('Anteprima')
                    ->limit(80)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Ultima Modifica')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('title')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
