<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SuperadminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_superadmin_can_create_companies_and_sees_every_company_in_the_list(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $superadmin = User::factory()->superadmin()->create();
        $superadmin->companies()->attach($companyA);

        $this->assertTrue($superadmin->can('create', Company::class));

        $response = $this->actingAs($superadmin)->get("/admin/{$companyA->slug}/companies");

        $response->assertOk();
        $response->assertSee('Company A');
        $response->assertSee('Company B');
    }

    /** @test */
    public function a_regular_manager_only_sees_their_own_company_in_the_list(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $manager = User::factory()->create();
        $manager->companies()->attach($companyA);

        $this->assertFalse($manager->can('create', Company::class));

        $response = $this->actingAs($manager)->get("/admin/{$companyA->slug}/companies");

        $response->assertOk();
        $response->assertSee('Company A');
        $response->assertDontSee('Company B');
    }

    /** @test */
    public function a_superadmin_sees_managers_of_every_company_in_the_list(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create(['name' => 'Manager Alpha']);
        $managerA->companies()->attach($companyA);

        $managerB = User::factory()->create(['name' => 'Manager Beta']);
        $managerB->companies()->attach($companyB);

        $superadmin = User::factory()->superadmin()->create();
        $superadmin->companies()->attach($companyA);

        $response = $this->actingAs($superadmin)->get("/admin/{$companyA->slug}/users");

        $response->assertOk();
        $response->assertSee('Manager Alpha');
        $response->assertSee('Manager Beta');
    }

    /** @test */
    public function a_regular_manager_only_sees_managers_of_their_own_company_in_the_list(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create(['name' => 'Manager Alpha']);
        $managerA->companies()->attach($companyA);

        $managerB = User::factory()->create(['name' => 'Manager Beta']);
        $managerB->companies()->attach($companyB);

        $response = $this->actingAs($managerA)->get("/admin/{$companyA->slug}/users");

        $response->assertOk();
        $response->assertSee('Manager Alpha');
        $response->assertDontSee('Manager Beta');
    }

    /** @test */
    public function a_superadmin_cannot_view_any_report_even_for_a_company_they_manage(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-SUPR-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $superadmin = User::factory()->superadmin()->create();
        $superadmin->companies()->attach($company);

        $this->assertFalse($superadmin->can('viewAny', Report::class));
        $this->assertFalse($superadmin->can('view', $report));
    }

    /** @test */
    public function a_regular_manager_cannot_promote_themselves_to_superadmin_via_the_form(): void
    {
        $company = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(EditUser::class, ['record' => $manager->id])
            ->fillForm(['is_superadmin' => true])
            ->call('save');

        $this->assertFalse($manager->fresh()->is_superadmin);
    }
}
