<?php

namespace Tests\Feature;

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Models\Company;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyLogoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function uploading_a_logo_stores_it_on_the_public_disk(): void
    {
        Storage::fake('public');

        $ownCompany = Company::create(['name' => 'Hassisto', 'slug' => 'hassisto']);
        $superadmin = User::factory()->superadmin()->create();
        $superadmin->companies()->attach($ownCompany);
        $this->actingAs($superadmin);
        Filament::setTenant($ownCompany);

        Livewire::test(CreateCompany::class)
            ->fillForm([
                'name' => 'Acme',
                'slug' => 'acme',
                'logo_path' => UploadedFile::fake()->image('logo.png'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $company = Company::where('slug', 'acme')->firstOrFail();

        Storage::disk('public')->assertExists($company->logo_path);
    }

    /** @test */
    public function the_avatar_url_resolves_against_the_public_disk_regardless_of_the_apps_default_disk(): void
    {
        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
            'logo_path' => 'company-logos/logo.png',
        ]);

        $this->assertSame(
            Storage::disk('public')->url('company-logos/logo.png'),
            $company->getFilamentAvatarUrl()
        );
    }

    /** @test */
    public function a_company_without_a_logo_has_no_avatar_url(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $this->assertNull($company->getFilamentAvatarUrl());
    }
}
