<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copiata!'),
                // Questo mostra le aziende collegate all'utente come delle "etichette" (badge)
                TextColumn::make('companies.name')
                    ->label('Aziende Assegnate')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtra gli utenti in base all'azienda
                SelectFilter::make('companies')
                    ->label('Filtra per Azienda')
                    ->relationship('companies', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                //  BulkActionGroup::make([
                //  Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
