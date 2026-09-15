<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Notifications\WebmasterInfo;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification as LaravelNotification;
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
                    ->formatStateUsing(fn (string $state): string => route('report.welcome', ['company' => $state]))
                    ->copyable()
                    ->copyable()
                    ->copyableState(fn ($record): string => route('report.welcome', ['company' => $record->slug]))
               //     ->copyStateUsing(fn($record): string => route('report.welcome', ['company' => $record->slug]))
                    ->copyMessage('Link copiato!')
                    ->searchable(),
                ColorColumn::make('brand_color')
                    ->label('Colore')
                    ->copyable(),
                TextColumn::make('shared_passcode')
                    ->label("Codice d'Accesso")
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('Codice copiato per essere inviato al cliente!'),
                TextColumn::make('webmaster_email')
                    ->label('Email Webmaster')
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->placeholder('Non impostata')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->modalContent(fn ($record) => new HtmlString('
        <div class="flex flex-col items-center justify-center p-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                '.QrCode::size(250)->generate(route('report.welcome', ['company' => $record->slug])).'
            </div>
            <p class="mt-4 text-sm text-gray-500 text-center">
                Inquadra questo codice per accedere al form di '.$record->name.'
            </p>
            <a href="'.route('report.qrcode', ['company' => $record->slug]).'"
               class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700">
                Scarica PNG
            </a>
            <p class="mt-2 text-xs text-gray-400 text-center">
                Puoi anche inviare direttamente questo link al webmaster: lo scarica in autonomia, senza passare dal pannello.
            </p>
        </div>
    ')),
                Action::make('send_webmaster_info')
                    ->label('Invia info al Webmaster')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('gray')
                    ->disabled(fn ($record): bool => blank($record->webmaster_email))
                    ->tooltip(fn ($record): ?string => blank($record->webmaster_email)
                        ? "Imposta prima l'Email Webmaster (modifica azienda)"
                        : null)
                    ->requiresConfirmation()
                    ->modalDescription(fn ($record) => "Invia a {$record->webmaster_email} il link pubblico, l'eventuale codice d'accesso, il QR code (allegato PNG) e gli snippet HTML pronti per il sito di {$record->name}.")
                    ->action(function ($record) {
                        LaravelNotification::route('mail', $record->webmaster_email)
                            ->notify(new WebmasterInfo($record));

                        FilamentNotification::make()
                            ->title('Email inviata al webmaster')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //  Tables\Actions\BulkActionGroup::make([
                //      Tables\Actions\DeleteBulkAction::make(),
                //   ]),
            ]);
    }
}
