<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyPublicHeaderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_name_is_shown_when_the_company_has_no_logo(): void
    {
        $company = Company::create(['name' => 'RACES Finance', 'slug' => 'races']);

        $response = $this->get(route('report.welcome', $company->slug));

        $response->assertOk();
        $response->assertSee('RACES Finance');
    }

    /** @test */
    public function the_name_is_hidden_next_to_the_logo_by_default(): void
    {
        $company = Company::create([
            'name' => 'RACES Finance',
            'slug' => 'races',
            'logo_path' => 'company-logos/races.png',
        ]);

        $response = $this->get(route('report.welcome', $company->slug));

        $response->assertOk();
        // Il nome compare comunque nel <title>: verifichiamo l'assenza del
        // frammento specifico dell'header, non del nome in tutta la pagina.
        $response->assertDontSee('<span class="text-xl font-bold text-gray-800">RACES Finance</span>', false);
    }

    /** @test */
    public function the_name_is_shown_next_to_the_logo_when_enabled(): void
    {
        $company = Company::create([
            'name' => 'RACES Finance',
            'slug' => 'races',
            'logo_path' => 'company-logos/races.png',
            'show_name_with_logo' => true,
        ]);

        $response = $this->get(route('report.welcome', $company->slug));

        $response->assertOk();
        $response->assertSee('<span class="text-xl font-bold text-gray-800">RACES Finance</span>', false);
    }

    /** @test */
    public function the_toggle_also_applies_to_the_reporter_guide_page(): void
    {
        $company = Company::create([
            'name' => 'RACES Finance',
            'slug' => 'races',
            'logo_path' => 'company-logos/races.png',
            'show_name_with_logo' => true,
        ]);

        $response = $this->get(route('report.guide', $company->slug));

        $response->assertOk();
        $response->assertSee('<span class="text-xl font-bold text-gray-800">RACES Finance</span>', false);
    }
}
