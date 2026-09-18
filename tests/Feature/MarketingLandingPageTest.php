<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingLandingPageTest extends TestCase
{
    /** @test */
    public function the_landing_page_boots_and_links_to_the_acme_demo(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('report.welcome', 'acme'), false);
        $response->assertSee(route('report.track', 'acme'), false);
        $response->assertSee(route('report.guide', 'acme'), false);
        $response->assertSee(route('report.qrcode', 'acme'), false);
        $response->assertSee(route('filament.admin.auth.login'), false);
        $response->assertSee(route('docs.manuale-utente'), false);
        $response->assertSee(route('docs.manuale-webmaster'), false);
        $response->assertSee(route('docs.manuale-tecnico'), false);
    }

    /** @test */
    public function the_manuals_are_viewable_and_downloadable_without_authentication(): void
    {
        $this->get(route('docs.manuale-tecnico'))->assertOk();
        $this->get(route('docs.manuale-webmaster'))->assertOk();
        $this->get(route('docs.manuale-utente'))->assertOk();
    }
}
