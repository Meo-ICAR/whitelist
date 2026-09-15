<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function changing_report_status_is_recorded_in_the_activity_log(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-AUDIT-0001',
            'status' => ReportStatus::New,
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $this->actingAs($manager);

        $report->update(['status' => ReportStatus::InProgress]);

        $activity = Activity::where('subject_type', Report::class)
            ->where('subject_id', $report->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($activity);
        $this->assertSame($manager->id, $activity->causer_id);
        $this->assertArrayHasKey('status', $activity->attribute_changes['attributes'] ?? []);
    }

    /** @test */
    public function activity_log_never_stores_the_encrypted_report_description(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-AUDIT-0002',
            'status' => ReportStatus::New,
            'title' => 'Report',
            'description' => 'Contenuto riservatissimo del segnalante',
        ]);

        $report->update(['status' => ReportStatus::Closed]);

        $activities = Activity::where('subject_type', Report::class)
            ->where('subject_id', $report->id)
            ->get();

        foreach ($activities as $activity) {
            $this->assertStringNotContainsString(
                'Contenuto riservatissimo del segnalante',
                json_encode($activity->attribute_changes)
            );
        }
    }

    /** @test */
    public function changing_company_passcode_logs_only_the_rotation_timestamp_not_the_value(): void
    {
        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
            'shared_passcode' => 'INITIAL01',
        ]);

        $company->update(['shared_passcode' => 'ROTATED99']);

        $activity = Activity::where('subject_type', Company::class)
            ->where('subject_id', $company->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($activity);
        $this->assertArrayNotHasKey('shared_passcode', $activity->attribute_changes['attributes'] ?? []);
        $this->assertArrayHasKey('passcode_rotated_at', $activity->attribute_changes['attributes'] ?? []);
    }
}
