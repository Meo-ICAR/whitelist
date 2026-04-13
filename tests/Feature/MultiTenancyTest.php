<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    private Company $companyA;
    private Company $companyB;
    private User $managerA;
    private User $managerB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->companyA = Company::create([
            'name' => 'Company A',
            'slug' => 'company-a',
        ]);

        $this->companyB = Company::create([
            'name' => 'Company B',
            'slug' => 'company-b',
        ]);

        $this->managerA = User::factory()->create();
        $this->managerA->companies()->attach($this->companyA);

        $this->managerB = User::factory()->create();
        $this->managerB->companies()->attach($this->companyB);
    }

    /** @test */
    public function scope_for_company_returns_only_reports_of_that_company(): void
    {
        Report::create([
            'company_id' => $this->companyA->id,
            'tracking_token' => 'WHSL-AAAA-0001',
            'status' => 'new',
            'title' => 'Report A1',
            'description' => 'Description A1',
        ]);

        Report::create([
            'company_id' => $this->companyA->id,
            'tracking_token' => 'WHSL-AAAA-0002',
            'status' => 'new',
            'title' => 'Report A2',
            'description' => 'Description A2',
        ]);

        Report::create([
            'company_id' => $this->companyB->id,
            'tracking_token' => 'WHSL-BBBB-0001',
            'status' => 'new',
            'title' => 'Report B1',
            'description' => 'Description B1',
        ]);

        $reportsForA = Report::forCompany($this->companyA)->get();
        $reportsForB = Report::forCompany($this->companyB)->get();

        $this->assertCount(2, $reportsForA);
        $this->assertCount(1, $reportsForB);
    }

    /** @test */
    public function manager_a_cannot_see_reports_of_company_b_via_scope(): void
    {
        Report::create([
            'company_id' => $this->companyB->id,
            'tracking_token' => 'WHSL-BBBB-0001',
            'status' => 'new',
            'title' => 'Confidential Report B',
            'description' => 'Sensitive data for company B',
        ]);

        // Manager A queries with their company scope
        $visibleReports = Report::forCompany($this->companyA)->get();

        $this->assertCount(0, $visibleReports);
    }

    /** @test */
    public function manager_b_cannot_see_reports_of_company_a_via_scope(): void
    {
        Report::create([
            'company_id' => $this->companyA->id,
            'tracking_token' => 'WHSL-AAAA-0001',
            'status' => 'new',
            'title' => 'Confidential Report A',
            'description' => 'Sensitive data for company A',
        ]);

        // Manager B queries with their company scope
        $visibleReports = Report::forCompany($this->companyB)->get();

        $this->assertCount(0, $visibleReports);
    }

    /** @test */
    public function company_reports_relationship_is_isolated_per_tenant(): void
    {
        Report::create([
            'company_id' => $this->companyA->id,
            'tracking_token' => 'WHSL-AAAA-0001',
            'status' => 'new',
            'title' => 'Report A',
            'description' => 'Description A',
        ]);

        Report::create([
            'company_id' => $this->companyB->id,
            'tracking_token' => 'WHSL-BBBB-0001',
            'status' => 'new',
            'title' => 'Report B',
            'description' => 'Description B',
        ]);

        $this->assertCount(1, $this->companyA->reports);
        $this->assertCount(1, $this->companyB->reports);

        $this->assertEquals('Report A', $this->companyA->reports->first()->title);
        $this->assertEquals('Report B', $this->companyB->reports->first()->title);
    }

    /** @test */
    public function user_is_assigned_only_to_their_own_company(): void
    {
        $this->assertTrue($this->managerA->companies->contains($this->companyA));
        $this->assertFalse($this->managerA->companies->contains($this->companyB));

        $this->assertTrue($this->managerB->companies->contains($this->companyB));
        $this->assertFalse($this->managerB->companies->contains($this->companyA));
    }

    /** @test */
    public function scope_for_company_does_not_return_reports_from_other_companies(): void
    {
        // Create 3 reports: 2 for A, 1 for B
        for ($i = 1; $i <= 2; $i++) {
            Report::create([
                'company_id' => $this->companyA->id,
                'tracking_token' => "WHSL-AAAA-000{$i}",
                'status' => 'new',
                'title' => "Report A{$i}",
                'description' => "Description A{$i}",
            ]);
        }

        Report::create([
            'company_id' => $this->companyB->id,
            'tracking_token' => 'WHSL-BBBB-0001',
            'status' => 'new',
            'title' => 'Report B1',
            'description' => 'Description B1',
        ]);

        $reportsForA = Report::forCompany($this->companyA)->get();

        // None of the reports for A should belong to company B
        foreach ($reportsForA as $report) {
            $this->assertEquals($this->companyA->id, $report->company_id);
        }
    }
}
