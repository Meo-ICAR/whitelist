<?php

namespace Tests\Feature;

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportSubmissionFlowTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'shared_passcode' => null,
        ]);
    }

    /** @test */
    public function reporter_can_submit_a_report_and_receive_a_pin(): void
    {
        $component = Livewire::test(PublicReportForm::class, ['company' => $this->company])
            ->set('data.title', 'Irregolarità contabili')
            ->set('data.description', 'Ho notato movimenti sospetti nei conti aziendali.')
            ->call('submit');

        $component->assertSet('isSubmitted', true);

        $pin = $component->get('trackingPin');
        $this->assertNotEmpty($pin);
        $this->assertMatchesRegularExpression('/^WHSL-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $pin);
    }

    /** @test */
    public function submitted_report_is_saved_in_database(): void
    {
        Livewire::test(PublicReportForm::class, ['company' => $this->company])
            ->set('data.title', 'Irregolarità contabili')
            ->set('data.description', 'Ho notato movimenti sospetti nei conti aziendali.')
            ->call('submit');

        $this->assertDatabaseCount('reports', 1);

        $report = Report::first();
        $this->assertEquals('Irregolarità contabili', $report->title);
        $this->assertEquals('new', $report->status);
        $this->assertEquals($this->company->id, $report->company_id);
    }

    /** @test */
    public function reporter_can_track_report_using_pin(): void
    {
        // Step 1: Submit report
        $submitComponent = Livewire::test(PublicReportForm::class, ['company' => $this->company])
            ->set('data.title', 'Segnalazione test')
            ->set('data.description', 'Descrizione della segnalazione.')
            ->call('submit');

        $pin = $submitComponent->get('trackingPin');

        // Step 2: Track report using PIN
        Livewire::test(PublicReportTracker::class, ['company' => $this->company])
            ->set('pin', $pin)
            ->call('accessReport')
            ->assertSet('errorMessage', '')
            ->assertNotSet('report', null);
    }

    /** @test */
    public function tracker_shows_error_for_invalid_pin(): void
    {
        Livewire::test(PublicReportTracker::class, ['company' => $this->company])
            ->set('pin', 'WHSL-FAKE-0000')
            ->call('accessReport')
            ->assertSet('errorMessage', 'PIN non valido o segnalazione inesistente.')
            ->assertSet('report', null);
    }

    /** @test */
    public function reporter_can_send_message_after_accessing_report(): void
    {
        // Step 1: Submit report
        $submitComponent = Livewire::test(PublicReportForm::class, ['company' => $this->company])
            ->set('data.title', 'Segnalazione test')
            ->set('data.description', 'Descrizione della segnalazione.')
            ->call('submit');

        $pin = $submitComponent->get('trackingPin');

        // Step 2: Access report via PIN
        $trackerComponent = Livewire::test(PublicReportTracker::class, ['company' => $this->company])
            ->set('pin', $pin)
            ->call('accessReport');

        // Step 3: Send a message as reporter
        $trackerComponent
            ->set('newMessage', 'Vorrei aggiungere ulteriori dettagli.')
            ->call('sendMessage');

        $report = Report::where('tracking_token', $pin)->first();
        $this->assertCount(1, $report->messages);

        $message = $report->messages->first();
        $this->assertEquals('Vorrei aggiungere ulteriori dettagli.', $message->body);
        $this->assertTrue($message->is_from_reporter);
    }

    /** @test */
    public function full_flow_submit_pin_track_chat(): void
    {
        // 1. Submit report
        $submitComponent = Livewire::test(PublicReportForm::class, ['company' => $this->company])
            ->set('data.title', 'Flusso completo')
            ->set('data.description', 'Test del flusso completo.')
            ->call('submit');

        $pin = $submitComponent->get('trackingPin');
        $this->assertMatchesRegularExpression('/^WHSL-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $pin);

        // 2. Verify report in DB
        $report = Report::where('tracking_token', $pin)->first();
        $this->assertNotNull($report);
        $this->assertEquals('new', $report->status);

        // 3. Track report
        $trackerComponent = Livewire::test(PublicReportTracker::class, ['company' => $this->company])
            ->set('pin', $pin)
            ->call('accessReport')
            ->assertSet('errorMessage', '');

        // 4. Reporter sends a message
        $trackerComponent
            ->set('newMessage', 'Messaggio dal segnalante.')
            ->call('sendMessage');

        // 5. Verify message saved with is_from_reporter = true
        $report->refresh();
        $this->assertCount(1, $report->messages);
        $this->assertTrue($report->messages->first()->is_from_reporter);
    }
}
