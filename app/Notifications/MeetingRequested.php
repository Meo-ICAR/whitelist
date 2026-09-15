<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingRequested extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Richiesta di incontro diretto dal segnalante')
            ->greeting("Ciao {$notifiable->name},")
            ->line('Il segnalante di una pratica ha richiesto un incontro diretto con il gestore.')
            ->action('Apri la segnalazione', url('/admin'))
            ->line('Per motivi di riservatezza, i dettagli non sono inclusi in questa email.');
    }
}
