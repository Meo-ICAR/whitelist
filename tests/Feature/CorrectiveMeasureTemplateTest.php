<?php

namespace Tests\Feature;

use App\Filament\Resources\CorrectiveMeasureTemplates\CorrectiveMeasureTemplateResource;
use App\Filament\Resources\CorrectiveMeasureTemplates\Pages\CreateCorrectiveMeasureTemplate;
use App\Filament\Resources\Reports\Pages\EditReport;
use App\Models\Company;
use App\Models\CorrectiveMeasureTemplate;
use App\Models\Report;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CorrectiveMeasureTemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_cannot_see_or_use_templates_of_another_company(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $templateA = CorrectiveMeasureTemplate::create([
            'company_id' => $companyA->id,
            'title' => 'Modello A',
            'content' => 'Contenuto riservato di A',
        ]);
        $managerB = User::factory()->create();
        $managerB->companies()->attach($companyB);

        $this->assertFalse($managerB->can('view', $templateA));
        $this->assertFalse($managerB->can('update', $templateA));
        $this->assertFalse($managerB->can('delete', $templateA));
    }

    /** @test */
    public function manager_can_manage_templates_of_their_own_company(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $template = CorrectiveMeasureTemplate::create([
            'company_id' => $company->id,
            'title' => 'Richiamo verbale',
            'content' => 'Testo standard del richiamo verbale.',
        ]);

        $this->assertTrue($manager->can('view', $template));
        $this->assertTrue($manager->can('update', $template));
        $this->assertTrue($manager->can('delete', $template));
    }

    /** @test */
    public function global_templates_are_viewable_by_any_company_but_not_editable(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $managerA = User::factory()->create();
        $managerA->companies()->attach($companyA);
        $managerB = User::factory()->create();
        $managerB->companies()->attach($companyB);

        $global = CorrectiveMeasureTemplate::create([
            'company_id' => null,
            'title' => 'Richiamo verbale (globale)',
            'content' => 'Testo standard.',
        ]);

        foreach ([$managerA, $managerB] as $manager) {
            $this->assertTrue($manager->can('view', $global));
            $this->assertFalse($manager->can('update', $global));
            $this->assertFalse($manager->can('delete', $global));
        }
    }

    /** @test */
    public function selecting_a_template_appends_its_content_to_the_corrective_measures_field(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-TPL-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $template = CorrectiveMeasureTemplate::create([
            'company_id' => $company->id,
            'title' => 'Richiamo verbale',
            'content' => 'Testo standard del richiamo verbale.',
        ]);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(EditReport::class, ['record' => $report->getRouteKey()])
            ->fillForm(['corrective_measure_template' => $template->id])
            ->assertFormSet(['corrective_measures' => 'Testo standard del richiamo verbale.']);
    }

    /** @test */
    public function selecting_a_second_template_appends_instead_of_replacing(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-TPL-0002',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $templateOne = CorrectiveMeasureTemplate::create([
            'company_id' => $company->id,
            'title' => 'Richiamo verbale',
            'content' => 'Primo testo.',
        ]);
        $templateTwo = CorrectiveMeasureTemplate::create([
            'company_id' => $company->id,
            'title' => 'Sospensione',
            'content' => 'Secondo testo.',
        ]);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(EditReport::class, ['record' => $report->getRouteKey()])
            ->fillForm(['corrective_measure_template' => $templateOne->id])
            ->fillForm(['corrective_measure_template' => $templateTwo->id])
            ->assertFormSet(['corrective_measures' => "Primo testo.\n\nSecondo testo."]);
    }

    /** @test */
    public function report_form_template_options_include_own_and_global_but_not_other_companys(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $manager = User::factory()->create();
        $manager->companies()->attach($companyA);

        $report = Report::create([
            'company_id' => $companyA->id,
            'tracking_token' => 'WHSL-TPL-0003',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        CorrectiveMeasureTemplate::create([
            'company_id' => $companyA->id,
            'title' => 'Modello di A',
            'content' => 'Contenuto A',
        ]);
        CorrectiveMeasureTemplate::create([
            'company_id' => $companyB->id,
            'title' => 'Modello di B',
            'content' => 'Contenuto B',
        ]);
        CorrectiveMeasureTemplate::create([
            'company_id' => null,
            'title' => 'Modello globale',
            'content' => 'Contenuto globale',
        ]);

        $this->actingAs($manager);
        Filament::setTenant($companyA);

        $component = Livewire::test(EditReport::class, ['record' => $report->getRouteKey()]);

        $options = $component->instance()
            ->form
            ->getComponent('corrective_measure_template')
            ->getOptions();

        $this->assertCount(2, $options);
        $this->assertContains('Modello di A', $options);
        $this->assertContains('Modello globale', $options);
        $this->assertNotContains('Modello di B', $options);
    }

    /** @test */
    public function resource_list_shows_own_and_global_templates_but_not_other_companys(): void
    {
        $companyA = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $companyB = Company::create(['name' => 'Company B', 'slug' => 'company-b']);

        $manager = User::factory()->create();
        $manager->companies()->attach($companyA);

        CorrectiveMeasureTemplate::create(['company_id' => $companyA->id, 'title' => 'Di A', 'content' => 'x']);
        CorrectiveMeasureTemplate::create(['company_id' => $companyB->id, 'title' => 'Di B', 'content' => 'x']);
        CorrectiveMeasureTemplate::create(['company_id' => null, 'title' => 'Globale', 'content' => 'x']);

        $this->actingAs($manager);
        Filament::setTenant($companyA);

        $records = CorrectiveMeasureTemplateResource::getEloquentQuery()->pluck('title');

        $this->assertTrue($records->contains('Di A'));
        $this->assertTrue($records->contains('Globale'));
        $this->assertFalse($records->contains('Di B'));
    }

    /** @test */
    public function creating_a_template_from_the_panel_assigns_it_to_the_current_tenant(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $this->actingAs($manager);
        Filament::setTenant($company);

        Livewire::test(CreateCorrectiveMeasureTemplate::class)
            ->fillForm([
                'title' => 'Nuovo modello',
                'content' => 'Contenuto del modello',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('corrective_measure_templates', [
            'title' => 'Nuovo modello',
            'company_id' => $company->id,
        ]);
    }
}
