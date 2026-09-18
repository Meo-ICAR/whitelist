<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebmasterInfo extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Company $company,
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
            ->subject("Canale di segnalazione {$this->company->name}: link, QR code e codice da pubblicare sul sito")
            // In copia al team che segue l'onboarding dei clienti, per avere
            // visibilità su quando le info sono state effettivamente inviate.
            ->cc('info@unicocompilance.eu')
            ->view('mail.webmaster-info', ['company' => $this->company])
            ->attachData(
                $this->company->qrCodePng(),
                'qrcode-'.$this->company->slug.'.png',
                ['mime' => 'image/png'],
            );
    }
}
