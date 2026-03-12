<?php

namespace App\Filament\Resources\Companies\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->defaultImageUrl(url('/images/default-company.png')),  // Immagine di fallback
                TextColumn::make('name')
                    ->label('Nome Azienda')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Link Segnalazioni')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->copyable()  // Cliccando copia lo slug negli appunti
                    ->copyMessage('Slug copiato!')
                    ->searchable(),
                ColorColumn::make('brand_color')
                    ->label('Colore')
                    ->copyable(),
                TextColumn::make('shared_passcode')
                    ->label("Codice d'Accesso")
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('Codice copiato per essere inviato al cliente!'),
                TextColumn::make('created_at')
                    ->label('Cliente dal')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Qui potresti aggiungere filtri (es. Clienti Attivi/Sospesi in futuro)
            ])
            ->actions([
                EditAction::make(),
                // Se hai creato la dashboard col PDF, puoi aggiungere qui anche l'azione di Download del QR!
                Action::make('generate_qr')
                    ->label('Genera QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code per le segnalazioni')
                    ->modalSubmitAction(false)  // Nasconde il tasto di conferma
                    ->modalContent(fn($record) => new HtmlString('
        <div class="flex flex-col items-center justify-center p-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                ' . QrCode::size(250)->generate(route('report.form', ['company' => $record->slug])) . '
            </div>
            <p class="mt-4 text-sm text-gray-500 text-center">
                Inquadra questo codice per accedere al form di ' . $record->name . '
            </p>
        </div>
    '))
            ])
            ->bulkActions([
                //  Tables\Actions\BulkActionGroup::make([
                //      Tables\Actions\DeleteBulkAction::make(),
                //   ]),
            ]);
    }
}
