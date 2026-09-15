<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcknowledgementDeadlineReminder extends Notification implements ShouldQueue
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
        $isOverdue = $this->report->acknowledgement_due_at->isPast();

        $mail = (new MailMessage)
            ->subject($isOverdue
                ? 'Termine scaduto: avviso di ricevimento non inviato'
                : 'In scadenza: avviso di ricevimento da inviare')
            ->greeting("Ciao {$notifiable->name},")
            ->action('Apri la segnalazione', url('/admin'));

        if ($isOverdue) {
            $mail->line('Il termine di legge di 7 giorni per l\'avviso di ricevimento di una segnalazione è scaduto senza che l\'avviso sia stato inviato.');
        } else {
            $mail->line('Una segnalazione si avvicina al termine di legge di 7 giorni per l\'invio dell\'avviso di ricevimento al segnalante.');
        }

        return $mail->line('Per motivi di riservatezza, i dettagli non sono inclusi in questa email.');
    }
}
