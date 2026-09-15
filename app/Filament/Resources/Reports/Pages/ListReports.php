<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Response;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportEsgStats')
                ->label('Esporta Statistiche ESG')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->action(function () {
                    $tenant = Filament::getTenant();
                    $stats = Report::anonymizedStatisticsFor($tenant);

                    $csv = collect($stats)
                        ->map(fn ($value, $label) => sprintf(
                            '"%s";"%s"',
                            str_replace('"', '""', (string) $label),
                            str_replace('"', '""', (string) $value),
                        ))
                        ->implode("\n");

                    return Response::streamDownload(
                        fn () => print($csv),
                        'statistiche-esg-' . $tenant->slug . '-' . now()->format('Y-m-d') . '.csv',
                        ['Content-Type' => 'text/csv'],
                    );
                }),
        ];
    }
}
