<?php

namespace Tests\Feature;

use App\Models\CorrectiveMeasureTemplate;
use Database\Seeders\CorrectiveMeasureTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorrectiveMeasureTemplateSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeder_creates_global_templates(): void
    {
        $this->seed(CorrectiveMeasureTemplateSeeder::class);

        $this->assertGreaterThanOrEqual(5, CorrectiveMeasureTemplate::count());
        $this->assertDatabaseHas('corrective_measure_templates', [
            'title' => 'Richiamo verbale',
            'company_id' => null,
        ]);
        $this->assertSame(0, CorrectiveMeasureTemplate::whereNotNull('company_id')->count());
    }

    /** @test */
    public function seeder_is_idempotent(): void
    {
        $this->seed(CorrectiveMeasureTemplateSeeder::class);
        $countAfterFirstRun = CorrectiveMeasureTemplate::count();

        $this->seed(CorrectiveMeasureTemplateSeeder::class);

        $this->assertSame($countAfterFirstRun, CorrectiveMeasureTemplate::count());
    }
}
