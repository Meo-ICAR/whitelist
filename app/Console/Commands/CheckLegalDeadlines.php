<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Notifications\AcknowledgementDeadlineReminder;
use App\Notifications\FeedbackDeadlineReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLegalDeadlines extends Command
{
    protected $signature = 'reports:check-deadlines';

    protected $description = 'Invia promemoria ai gestori per le scadenze di legge (avviso di ricevimento 7gg, riscontro finale 3 mesi)';

    // Giorni di anticipo per il promemoria "in scadenza" (oltre ai promemoria
    // per le pratiche già scadute, sempre inviati).
    private const WARNING_WINDOW_DAYS = 2;
    private const FEEDBACK_WARNING_WINDOW_DAYS = 14;

    public function handle(): int
    {
        $acknowledgementCount = $this->remindAcknowledgements();
        $feedbackCount = $this->remindFeedbackDeadlines();

        $this->info("Promemoria avviso di ricevimento inviati: {$acknowledgementCount}.");
        $this->info("Promemoria riscontro finale inviati: {$feedbackCount}.");

        return self::SUCCESS;
    }

    private function remindAcknowledgements(): int
    {
        $reports = Report::query()
            ->acknowledgementDeadlineWithin(self::WARNING_WINDOW_DAYS)
            ->where(function ($query) {
                $query->whereNull('acknowledgement_reminder_sent_at')
                    ->orWhere('acknowledgement_reminder_sent_at', '<=', now()->subDay());
            })
            ->with('company.users')
            ->get();

        foreach ($reports as $report) {
            Notification::send($report->company->users, new AcknowledgementDeadlineReminder($report));
            $report->forceFill(['acknowledgement_reminder_sent_at' => now()])->save();
        }

        return $reports->count();
    }

    private function remindFeedbackDeadlines(): int
    {
        $reports = Report::query()
            ->feedbackDeadlineWithin(self::FEEDBACK_WARNING_WINDOW_DAYS)
            ->where(function ($query) {
                $query->whereNull('feedback_reminder_sent_at')
                    ->orWhere('feedback_reminder_sent_at', '<=', now()->subDay());
            })
            ->with('company.users')
            ->get();

        foreach ($reports as $report) {
            Notification::send($report->company->users, new FeedbackDeadlineReminder($report));
            $report->forceFill(['feedback_reminder_sent_at' => now()])->save();
        }

        return $reports->count();
    }
}
