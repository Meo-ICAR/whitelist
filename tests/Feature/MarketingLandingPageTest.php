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
    }
}
