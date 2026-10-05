<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourcePagesBootTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_report_list_and_edit_pages_boot_without_errors(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-BOOT-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $this->actingAs($manager)->get("/admin/{$company->slug}");

        // Regressione: 'created_at' arriva come stringa (non Carbon) quando
        // il form idrata i valori dal record; ->format() falliva.
        $this->get("/admin/{$company->slug}/reports/{$report->id}/edit")
            ->assertOk();

        $this->get("/admin/{$company->slug}/reports")
            ->assertOk();
    }

    /** @test */
    public function the_company_edit_page_boots_without_errors(): void
    {
        // Regressione: ->revealable() su un campo che non è ->password()
        // manda in errore fatale ogni pagina che mostra questo form.
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager)->get("/admin/{$company->slug}");

        $this->get("/admin/{$company->slug}/companies/{$company->id}/edit")
            ->assertOk();
    }

    /** @test */
    public function the_test_pratico_page_boots_and_links_to_the_configure_test_and_view_steps(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $response = $this->actingAs($manager)->get("/admin/{$company->slug}/test-pratico");

        $response->assertOk();
        $response->assertSee("/admin/{$company->slug}/companies/{$company->id}/edit", false);
        $response->assertSee(route('report.welcome', $company->slug), false);
        $response->assertSee("/admin/{$company->slug}/reports", false);
    }
}
