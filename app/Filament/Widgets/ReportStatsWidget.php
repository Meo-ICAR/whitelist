<?php

namespace App\Filament\Widgets;

use App\Enums\ReportStatus;
use App\Models\Report;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = Report::query();

        if ($tenant) {
            $query->where('company_id', $tenant->id);
        }

        $counts = $query->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            Stat::make('Nuove', $counts[ReportStatus::New->value] ?? 0)
                ->description('Segnalazioni in attesa')
                ->color('danger'),

            Stat::make('In Lavorazione', $counts[ReportStatus::InProgress->value] ?? 0)
                ->description('Segnalazioni in corso')
                ->color('warning'),

            Stat::make('Chiuse', $counts[ReportStatus::Closed->value] ?? 0)
                ->description('Segnalazioni risolte')
                ->color('success'),
        ];
    }
}
