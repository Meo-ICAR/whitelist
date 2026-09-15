<?php

namespace Tests\Feature;

use App\Livewire\PublicReportForm;
use App\Models\Company;
use App\Models\User;
use App\Notifications\NewReportReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class NewReportNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function managers_of_the_company_are_notified_when_a_new_report_arrives(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->set('data.title', 'Irregolarità contabili')
            ->set('data.description', 'Movimenti sospetti nei conti.')
            ->call('submit');

        Notification::assertSentTo($manager, NewReportReceived::class);
    }

    /** @test */
    public function managers_of_other_companies_are_not_notified(): void
    {
        Notification::fake();

        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerB = User::factory()->create();
        $managerB->companies()->attach($companyB);

        Livewire::test(PublicReportForm::class, ['company' => $companyA])
            ->set('data.title', 'Irregolarità contabili')
            ->set('data.description', 'Movimenti sospetti nei conti.')
            ->call('submit');

        Notification::assertNotSentTo($managerB, NewReportReceived::class);
    }

    /** @test */
    public function notification_email_never_contains_the_report_description(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->set('data.title', 'Irregolarità contabili')
            ->set('data.description', 'Contenuto riservatissimo del segnalante')
            ->call('submit');

        Notification::assertSentTo($manager, NewReportReceived::class, function (NewReportReceived $notification) use ($manager) {
            $mail = $notification->toMail($manager);
            $rendered = json_encode($mail);

            return ! str_contains($rendered, 'Contenuto riservatissimo del segnalante');
        });
    }
}
