<?php

namespace Tests\Feature;

use App\Filament\Pages\TestPratico;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Models\Company;
use App\Models\User;
use App\Notifications\WebmasterInfo;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyWebmasterInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sending_webmaster_info_notifies_the_configured_email(): void
    {
        Notification::fake();

        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
            'shared_passcode' => 'SECRET99',
            'webmaster_email' => 'webmaster@acme.test',
        ]);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(ListCompanies::class)
            ->callTableAction('send_webmaster_info', $company);

        Notification::assertSentOnDemand(
            WebmasterInfo::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'webmaster@acme.test'
        );
    }

    /** @test */
    public function sending_webmaster_info_from_the_test_pratico_page_notifies_the_configured_email(): void
    {
        Notification::fake();

        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
            'webmaster_email' => 'webmaster@acme.test',
        ]);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(TestPratico::class)
            ->callAction('sendWebmasterInfo');

        Notification::assertSentOnDemand(
            WebmasterInfo::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'webmaster@acme.test'
        );
    }

    /** @test */
    public function the_send_webmaster_info_action_is_disabled_without_a_configured_email(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(TestPratico::class)
            ->assertActionDisabled('sendWebmasterInfo');
    }

    /** @test */
    public function the_webmaster_email_is_sent_in_cc_to_the_address_configured_in_env(): void
    {
        config(['mail.webmaster_cc_address' => 'onboarding@example.test']);

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme', 'webmaster_email' => 'webmaster@acme.test']);

        $mailMessage = (new WebmasterInfo($company))->toMail((object) ['routes' => ['mail' => $company->webmaster_email]]);

        $this->assertContains(['onboarding@example.test', null], $mailMessage->cc);
    }

    /** @test */
    public function no_cc_is_added_when_there_is_no_configured_address_and_no_managers(): void
    {
        config(['mail.webmaster_cc_address' => null]);

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme', 'webmaster_email' => 'webmaster@acme.test']);

        $mailMessage = (new WebmasterInfo($company))->toMail((object) ['routes' => ['mail' => $company->webmaster_email]]);

        $this->assertEmpty($mailMessage->cc);
    }

    /** @test */
    public function the_webmaster_email_is_also_sent_in_cc_to_the_companys_managers(): void
    {
        config(['mail.webmaster_cc_address' => null]);

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme', 'webmaster_email' => 'webmaster@acme.test']);
        $managerA = User::factory()->create(['email' => 'manager-a@acme.test']);
        $managerB = User::factory()->create(['email' => 'manager-b@acme.test']);
        $company->users()->attach([$managerA->id, $managerB->id]);

        $mailMessage = (new WebmasterInfo($company))->toMail((object) ['routes' => ['mail' => $company->webmaster_email]]);

        $this->assertContains(['manager-a@acme.test', null], $mailMessage->cc);
        $this->assertContains(['manager-b@acme.test', null], $mailMessage->cc);
    }

    /** @test */
    public function the_webmaster_email_explains_why_it_was_sent_and_links_to_the_webmaster_manual(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme', 'webmaster_email' => 'webmaster@acme.test']);

        $rendered = (new WebmasterInfo($company))
            ->toMail((object) ['routes' => ['mail' => $company->webmaster_email]])
            ->render();

        $this->assertStringContainsString('Perché ricevi questa email', (string) $rendered);
        $this->assertStringContainsString(route('docs.manuale-webmaster'), (string) $rendered);
    }

    /** @test */
    public function qr_code_png_encodes_the_companys_public_report_link(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $png = $company->qrCodePng();

        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $png);
    }
}
