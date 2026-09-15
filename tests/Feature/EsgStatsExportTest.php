<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EsgStatsExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function statistics_are_fully_anonymized_and_aggregated(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $otherCompany = Company::create(['name' => 'Other', 'slug' => 'other']);

        Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-ESG-0001',
            'status' => ReportStatus::New,
            'title' => 'Riservatissimo: nome del segnalante',
            'description' => 'Contenuto molto riservato',
        ]);
        Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-ESG-0002',
            'status' => ReportStatus::Closed,
            'title' => 'Altra segnalazione',
            'description' => 'Altro contenuto',
        ]);
        // Segnalazione di un'altra azienda: non deve influenzare le statistiche.
        Report::create([
            'company_id' => $otherCompany->id,
            'tracking_token' => 'WHSL-ESG-0003',
            'status' => ReportStatus::New,
            'title' => 'Segnalazione altra azienda',
            'description' => 'Contenuto',
        ]);

        $stats = Report::anonymizedStatisticsFor($company);

        $this->assertSame(2, $stats['Segnalazioni totali']);
        $this->assertSame(1, $stats['Nuove']);
        $this->assertSame(1, $stats['Chiuse']);

        // Nessun valore identificativo o di contenuto tra le chiavi/valori esportati.
        $flattened = json_encode($stats);
        $this->assertStringNotContainsString('Riservatissimo', $flattened);
        $this->assertStringNotContainsString('WHSL-ESG', $flattened);
        $this->assertStringNotContainsString('riservato', $flattened);
    }
}
