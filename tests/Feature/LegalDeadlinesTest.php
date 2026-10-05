<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use App\Notifications\AcknowledgementDeadlineReminder;
use App\Notifications\FeedbackDeadlineReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LegalDeadlinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_reports_get_legal_deadlines_set_automatically(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $this->assertNotNull($report->acknowledgement_due_at);
        $this->assertNotNull($report->feedback_due_at);
        $this->assertTrue($report->acknowledgement_due_at->isSameDay(now()->addDays(7)));
        $this->assertTrue($report->feedback_due_at->isSameDay(now()->addMonths(3)));
    }

    /** @test */
    public function acknowledging_a_report_sets_the_timestamp_and_posts_an_automatic_message(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0002',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $report->acknowledge();

        $this->assertNotNull($report->fresh()->acknowledged_at);
        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'is_from_reporter' => false,
        ]);
    }

    /** @test */
    public function command_notifies_managers_of_overdue_acknowledgement(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0003',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);
        $report->forceFill(['acknowledgement_due_at' => now()->subDay()])->save();

        $this->artisan('reports:check-deadlines')->assertExitCode(0);

        Notification::assertSentTo($manager, AcknowledgementDeadlineReminder::class);
    }

    /** @test */
    public function command_does_not_notify_twice_within_the_same_day(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0004',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);
        $report->forceFill(['acknowledgement_due_at' => now()->subDay()])->save();

        $this->artisan('reports:check-deadlines');
        $this->artisan('reports:check-deadlines');

        Notification::assertSentToTimes($manager, AcknowledgementDeadlineReminder::class, 1);
    }

    /** @test */
    public function command_does_not_notify_for_acknowledged_reports(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0005',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);
        $report->forceFill([
            'acknowledgement_due_at' => now()->subDay(),
            'acknowledged_at' => now(),
        ])->save();

        $this->artisan('reports:check-deadlines');

        Notification::assertNotSentTo($manager, AcknowledgementDeadlineReminder::class);
    }

    /** @test */
    public function command_notifies_managers_of_overdue_feedback_deadline(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0006',
            'status' => ReportStatus::InProgress,
            'title' => 'Report',
            'description' => 'Description',
        ]);
        $report->forceFill(['feedback_due_at' => now()->subDay()])->save();

        $this->artisan('reports:check-deadlines');

        Notification::assertSentTo($manager, FeedbackDeadlineReminder::class);
    }

    /** @test */
    public function command_does_not_notify_feedback_deadline_for_closed_reports(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DDL-0007',
            'status' => ReportStatus::Closed,
            'title' => 'Report',
            'description' => 'Description',
        ]);
        $report->forceFill(['feedback_due_at' => now()->subDay()])->save();

        $this->artisan('reports:check-deadlines');

        Notification::assertNotSentTo($manager, FeedbackDeadlineReminder::class);
    }
}
