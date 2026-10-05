<?php

namespace Tests\Feature;

use App\Livewire\PublicReportTracker;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use App\Notifications\MeetingRequested;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class MeetingRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reporter_can_request_a_direct_meeting(): void
    {
        Notification::fake();

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-MEET-0001',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        Livewire::test(PublicReportTracker::class, ['company' => $company])
            ->set('pin', 'WHSL-MEET-0001')
            ->call('accessReport')
            ->call('requestMeeting');

        $report->refresh();

        $this->assertNotNull($report->meeting_requested_at);
        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'is_from_reporter' => true,
        ]);
        Notification::assertSentTo($manager, MeetingRequested::class);
    }

    /** @test */
    public function requesting_a_meeting_twice_does_not_duplicate_it(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-MEET-0002',
            'status' => 'new',
            'title' => 'Report',
            'description' => 'Description',
        ]);

        $component = Livewire::test(PublicReportTracker::class, ['company' => $company])
            ->set('pin', 'WHSL-MEET-0002')
            ->call('accessReport')
            ->call('requestMeeting')
            ->call('requestMeeting');

        $this->assertDatabaseCount('messages', 1);
    }
}
