<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAnonymizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function old_closed_reports_are_anonymized_by_the_command(): void
    {
        config(['whistleblowing.retention_months' => 24]);

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $oldReport = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-RET-0001',
            'status' => ReportStatus::Closed,
            'title' => 'Vecchia segnalazione',
            'description' => 'Contenuto molto riservato',
        ]);
        $oldReport->messages()->create(['body' => 'Messaggio riservato', 'is_from_reporter' => true]);
        $oldReport->forceFill(['updated_at' => now()->subMonths(25)])->saveQuietly();

        $this->artisan('reports:anonymize-expired')->assertExitCode(0);

        $oldReport->refresh();

        $this->assertNotNull($oldReport->anonymized_at);
        $this->assertEquals('[Segnalazione anonimizzata]', $oldReport->title);
        $this->assertStringNotContainsString('Contenuto molto riservato', $oldReport->description);
        $this->assertStringNotContainsString('Messaggio riservato', $oldReport->messages->first()->body);
    }

    /** @test */
    public function recently_closed_reports_are_not_anonymized(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $recentReport = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-RET-0002',
            'status' => ReportStatus::Closed,
            'title' => 'Segnalazione recente',
            'description' => 'Contenuto attuale',
        ]);

        $this->artisan('reports:anonymize-expired')->assertExitCode(0);

        $recentReport->refresh();

        $this->assertNull($recentReport->anonymized_at);
        $this->assertEquals('Segnalazione recente', $recentReport->title);
    }

    /** @test */
    public function open_reports_are_never_anonymized_regardless_of_age(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $oldOpenReport = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-RET-0003',
            'status' => ReportStatus::New,
            'title' => 'Ancora aperta',
            'description' => 'Contenuto',
        ]);
        $oldOpenReport->forceFill(['updated_at' => now()->subYears(5)])->saveQuietly();

        $this->artisan('reports:anonymize-expired')->assertExitCode(0);

        $oldOpenReport->refresh();

        $this->assertNull($oldOpenReport->anonymized_at);
    }
}
