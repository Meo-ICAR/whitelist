<?php

namespace App\Filament\Widgets;

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

        $new        = (clone $query)->where('status', 'new')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $closed     = (clone $query)->where('status', 'closed')->count();

        return [
            Stat::make('Nuove', $new)
                ->description('Segnalazioni in attesa')
                ->color('danger'),

            Stat::make('In Lavorazione', $inProgress)
                ->description('Segnalazioni in corso')
                ->color('warning'),

            Stat::make('Chiuse', $closed)
                ->description('Segnalazioni risolte')
                ->color('success'),
        ];
    }
}
