<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_cannot_view_or_update_a_report_of_another_company(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create();
        $managerA->companies()->attach($companyA);

        $reportB = Report::create([
            'company_id' => $companyB->id,
            'tracking_token' => 'WHSL-POL-0001',
            'status' => 'new',
            'title' => 'Report B',
            'description' => 'Description',
        ]);

        $this->assertFalse($managerA->can('view', $reportB));
        $this->assertFalse($managerA->can('update', $reportB));
    }

    /** @test */
    public function manager_can_view_and_update_reports_of_their_own_company(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-POL-0002',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $this->assertTrue($manager->can('view', $report));
        $this->assertTrue($manager->can('update', $report));
    }

    /** @test */
    public function nobody_can_create_or_delete_reports_from_the_panel(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-POL-0003',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $this->assertFalse($manager->can('create', Report::class));
        $this->assertFalse($manager->can('delete', $report));
    }

    /** @test */
    public function manager_cannot_create_or_delete_companies_from_the_panel(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->assertFalse($manager->can('create', Company::class));
        $this->assertFalse($manager->can('delete', $company));
        $this->assertTrue($manager->can('update', $company));
    }

    /** @test */
    public function manager_cannot_view_messages_belonging_to_another_companys_report(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create();
        $managerA->companies()->attach($companyA);

        $reportB = Report::create([
            'company_id' => $companyB->id,
            'tracking_token' => 'WHSL-POL-0004',
            'status' => 'new',
            'title' => 'Report B',
            'description' => 'Description',
        ]);

        $message = $reportB->messages()->create([
            'body' => 'Risposta del gestore',
            'is_from_reporter' => false,
        ]);

        $this->assertFalse($managerA->can('view', $message));
    }

    /** @test */
    public function nobody_can_update_or_delete_messages(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-POL-0005',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $message = $report->messages()->create([
            'body' => 'Risposta del gestore',
            'is_from_reporter' => false,
        ]);

        $this->assertFalse($manager->can('update', $message));
        $this->assertFalse($manager->can('delete', $message));
    }
}
