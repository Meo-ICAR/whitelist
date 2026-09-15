<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackDeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Report $report,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isOverdue = $this->report->feedback_due_at->isPast();

        $mail = (new MailMessage)
            ->subject($isOverdue
                ? 'Termine scaduto: riscontro finale non inviato'
                : 'In scadenza: riscontro finale entro 3 mesi')
            ->greeting("Ciao {$notifiable->name},")
            ->action('Apri la segnalazione', url('/admin'));

        if ($isOverdue) {
            $mail->line('Il termine di legge di 3 mesi per fornire un riscontro finale al segnalante è scaduto e la pratica non risulta chiusa.');
        } else {
            $mail->line('Una segnalazione si avvicina al termine di legge di 3 mesi per il riscontro finale al segnalante.');
        }

        return $mail->line('Per motivi di riservatezza, i dettagli non sono inclusi in questa email.');
    }
}
