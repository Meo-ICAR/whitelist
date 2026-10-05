<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportReceived extends Notification implements ShouldQueue
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
        // Sicurezza: l'email non contiene mai oggetto/descrizione della
        // segnalazione, che restano riservati e visibili solo dopo il login
        // nel pannello di gestione.
        return (new MailMessage)
            ->subject('Nuova segnalazione ricevuta')
            ->greeting("Ciao {$notifiable->name},")
            ->line('È stata ricevuta una nuova segnalazione che richiede la tua attenzione.')
            ->line('Per motivi di riservatezza, i dettagli non sono inclusi in questa email.')
            ->action('Visualizza la segnalazione', url('/admin'))
            ->line('Accedi al pannello di gestione per prenderla in carico.');
    }
}
