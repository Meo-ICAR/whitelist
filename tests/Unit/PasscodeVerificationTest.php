<?php

namespace Tests\Unit;

use App\Livewire\PublicReportForm;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PasscodeVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function form_is_accessible_without_passcode_when_company_has_no_passcode(): void
    {
        $company = Company::create([
            'name' => 'Open Company',
            'slug' => 'open-company',
            'shared_passcode' => null,
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', true);
    }

    /** @test */
    public function form_is_accessible_without_passcode_when_company_has_empty_passcode(): void
    {
        $company = Company::create([
            'name' => 'Open Company',
            'slug' => 'open-company',
            'shared_passcode' => '',
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', true);
    }

    /** @test */
    public function form_is_locked_when_company_has_passcode(): void
    {
        $company = Company::create([
            'name' => 'Protected Company',
            'slug' => 'protected-company',
            'shared_passcode' => 'secret123',
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', false);
    }

    /** @test */
    public function correct_passcode_unlocks_the_form(): void
    {
        $company = Company::create([
            'name' => 'Protected Company',
            'slug' => 'protected-company',
            'shared_passcode' => 'secret123',
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', false)
            ->set('passcodeInput', 'secret123')
            ->call('verifyPasscode')
            ->assertSet('passcodeVerified', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function wrong_passcode_does_not_unlock_the_form(): void
    {
        $company = Company::create([
            'name' => 'Protected Company',
            'slug' => 'protected-company',
            'shared_passcode' => 'secret123',
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', false)
            ->set('passcodeInput', 'wrongpassword')
            ->call('verifyPasscode')
            ->assertSet('passcodeVerified', false)
            ->assertHasErrors(['passcodeInput']);
    }

    /** @test */
    public function empty_passcode_input_does_not_unlock_protected_form(): void
    {
        $company = Company::create([
            'name' => 'Protected Company',
            'slug' => 'protected-company',
            'shared_passcode' => 'secret123',
        ]);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->assertSet('passcodeVerified', false)
            ->set('passcodeInput', '')
            ->call('verifyPasscode')
            ->assertSet('passcodeVerified', false)
            ->assertHasErrors(['passcodeInput']);
    }
}
