<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaDownloadSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeReportWithEvidence(Company $company, string $token): Report
    {
        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => $token,
            'status' => 'new',
            'title' => 'Report with evidence',
            'description' => 'Description',
        ]);

        $report->addMediaFromString('evidence content')
            ->usingFileName('evidence.txt')
            ->toMediaCollection('evidence');

        return $report;
    }

    /** @test */
    public function manager_cannot_download_evidence_from_another_companys_report(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $reportB = $this->makeReportWithEvidence($companyB, 'WHSL-IDOR-0001');
        $media = $reportB->getFirstMedia('evidence');

        $managerA = User::factory()->create();
        $managerA->companies()->attach($companyA);

        $response = $this->actingAs($managerA)
            ->get(route('media.download', ['media' => $media->id]));

        $response->assertForbidden();
    }

    /** @test */
    public function manager_can_download_evidence_from_their_own_companys_report(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);

        $report = $this->makeReportWithEvidence($company, 'WHSL-IDOR-0002');
        $media = $report->getFirstMedia('evidence');

        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $response = $this->actingAs($manager)
            ->get(route('media.download', ['media' => $media->id]));

        $response->assertOk();
    }

    /** @test */
    public function guest_cannot_download_evidence(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $report = $this->makeReportWithEvidence($company, 'WHSL-IDOR-0003');
        $media = $report->getFirstMedia('evidence');

        $response = $this->get(route('media.download', ['media' => $media->id]));

        $response->assertStatus(302);
    }
}
