<?php

namespace Tests\Feature;

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        RateLimiter::clear('*');

        parent::tearDown();
    }

    /** @test */
    public function pin_lookup_is_throttled_after_repeated_failed_attempts(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $component = Livewire::test(PublicReportTracker::class, ['company' => $company]);

        for ($i = 0; $i < 10; $i++) {
            $component->set('pin', 'WHSL-0000-000' . $i)->call('accessReport');
        }

        $component->set('pin', 'WHSL-AAAA-AAAA')->call('accessReport');

        $component->assertSet('report', null);
        $this->assertStringContainsString('Troppi tentativi', $component->get('errorMessage'));
    }

    /** @test */
    public function passcode_verification_is_throttled_after_repeated_failed_attempts(): void
    {
        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
            'shared_passcode' => 'secret123',
        ]);

        $component = Livewire::test(PublicReportForm::class, ['company' => $company]);

        for ($i = 0; $i < 10; $i++) {
            $component->set('passcodeInput', 'wrong')->call('verifyPasscode');
        }

        $component->set('passcodeInput', 'secret123')->call('verifyPasscode');

        $component->assertSet('passcodeVerified', false);
        $component->assertHasErrors(['passcodeInput']);
    }

    /** @test */
    public function accessing_report_only_finds_reports_belonging_to_current_company(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        Report::create([
            'company_id' => $companyB->id,
            'tracking_token' => 'WHSL-CROSS-0001',
            'status' => 'new',
            'title' => 'Report B',
            'description' => 'Description',
        ]);

        Livewire::test(PublicReportTracker::class, ['company' => $companyA])
            ->set('pin', 'WHSL-CROSS-0001')
            ->call('accessReport')
            ->assertSet('report', null)
            ->assertSet('errorMessage', 'PIN non valido o segnalazione inesistente.');
    }
}
