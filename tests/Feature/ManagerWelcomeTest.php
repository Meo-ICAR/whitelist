<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\Company;
use App\Models\User;
use App\Notifications\ManagerWelcome;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ManagerWelcomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_manager_sends_a_welcome_email_with_the_plain_password(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $admin = User::factory()->create();
        $admin->companies()->attach($company);

        $this->actingAs($admin);
        Filament::setTenant($company);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Mario Rossi',
                'email' => 'mario.rossi@example.test',
                'password' => 'PasswordProvvisoria123!',
                'companies' => [$company->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newManager = User::where('email', 'mario.rossi@example.test')->firstOrFail();

        Notification::assertSentTo(
            $newManager,
            ManagerWelcome::class,
            function (ManagerWelcome $notification) use ($newManager, $company) {
                $mail = $notification->toMail($newManager);

                return str_contains($mail->render(), 'PasswordProvvisoria123!')
                    && str_contains($mail->render(), $company->name)
                    && str_contains($mail->render(), route('report.guide', $company->slug))
                    && str_contains($mail->render(), route('docs.manuale-utente'));
            }
        );
    }

    /** @test */
    public function the_welcome_email_lists_every_company_the_manager_is_assigned_to(): void
    {
        Notification::fake();

        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);
        $admin = User::factory()->create();
        $admin->companies()->attach($companyA);

        $this->actingAs($admin);
        Filament::setTenant($companyA);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Mario Rossi',
                'email' => 'mario.rossi@example.test',
                'password' => 'PasswordProvvisoria123!',
                'companies' => [$companyA->id, $companyB->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newManager = User::where('email', 'mario.rossi@example.test')->firstOrFail();

        Notification::assertSentTo(
            $newManager,
            ManagerWelcome::class,
            function (ManagerWelcome $notification) use ($newManager, $companyA, $companyB) {
                $rendered = $notification->toMail($newManager)->render();

                return str_contains($rendered, route('report.guide', $companyA->slug))
                    && str_contains($rendered, route('report.guide', $companyB->slug));
            }
        );
    }
}
