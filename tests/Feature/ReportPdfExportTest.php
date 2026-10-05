<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPdfExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_can_export_a_pdf_of_their_own_companys_report(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-PDF-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $response = $this->actingAs($manager)->get(route('reports.pdf', $report));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function manager_cannot_export_a_pdf_of_another_companys_report(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create();
        $managerA->companies()->attach($companyA);

        $reportB = Report::create([
            'company_id' => $companyB->id,
            'tracking_token' => 'WHSL-PDF-0002',
            'status' => 'new',
            'title' => 'Report B',
            'description' => 'Description',
        ]);

        $response = $this->actingAs($managerA)->get(route('reports.pdf', $reportB));

        $response->assertForbidden();
    }
}
