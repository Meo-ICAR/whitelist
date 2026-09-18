<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ManagerWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly User $manager,
        // La password in chiaro esiste solo nell'istante della creazione
        // dell'account (dopodiché è irrecuperabile, essendo hashata): va
        // passata qui esplicitamente da chi crea l'utente, non può essere
        // ricavata in un secondo momento.
        private readonly string $plainPassword,
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
            ->subject('Benvenuto su '.config('app.name').': le tue credenziali di accesso')
            ->view('mail.manager-welcome', [
                'manager' => $this->manager,
                'companies' => $this->manager->companies,
                'plainPassword' => $this->plainPassword,
            ]);
    }
}
