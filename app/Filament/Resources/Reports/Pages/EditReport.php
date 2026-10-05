<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('acknowledge')
                ->label('Invia Avviso di Ricevimento')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Conferma l\'invio al segnalante dell\'avviso di ricevimento previsto dalla legge entro 7 giorni. Verrà registrato un messaggio automatico nella chat della pratica.')
                ->visible(fn () => is_null($this->record->acknowledged_at))
                ->action(function () {
                    $this->record->acknowledge();
                    $this->refreshFormData(['acknowledged_at']);
                }),
            Action::make('exportPdf')
                ->label('Esporta PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->url(fn () => route('reports.pdf', $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
