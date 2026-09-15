<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    private function belongsToMessageCompany(User $user, Message $message): bool
    {
        return $user->companies()->where('companies.id', $message->report->company_id)->exists();
    }

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Message $message): bool
    {
        return $this->belongsToMessageCompany($user, $message);
    }

    public function create(User $user): bool
    {
        return $user->companies()->exists();
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
