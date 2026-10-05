<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    // Il superadmin SaaS non deve mai poter leggere i messaggi di nessuna
    // segnalazione (vedi ReportPolicy): già bloccato indirettamente perché
    // non può aprire nessun Report, ma lo escludiamo esplicitamente qui
    // come difesa in profondità.
    private function belongsToMessageCompany(User $user, Message $message): bool
    {
        if ($user->is_superadmin) {
            return false;
        }

        return $user->companies()->where('companies.id', $message->report->company_id)->exists();
    }

    public function viewAny(User $user): bool
    {
        return (! $user->is_superadmin) && $user->companies()->exists();
    }

    public function view(User $user, Message $message): bool
    {
        return $this->belongsToMessageCompany($user, $message);
    }

    public function create(User $user): bool
    {
        return (! $user->is_superadmin) && $user->companies()->exists();
    }

    public function update(User $user, Message $message): bool
    {
        // Integrità legale dell'audit trail: nessun messaggio è modificabile
        // dopo l'invio, né dal segnalante né dal gestore.
        return false;
    }

    public function delete(User $user, Message $message): bool
    {
        return false;
    }

    public function restore(User $user, Message $message): bool
    {
        return false;
    }

    public function forceDelete(User $user, Message $message): bool
    {
        return false;
    }
}
