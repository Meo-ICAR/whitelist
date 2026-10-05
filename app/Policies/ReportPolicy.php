<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Difesa in profondità: oltre allo scoping automatico del tenant di
     * Filament, verifichiamo esplicitamente che l'utente appartenga alla
     * company della segnalazione, per non dipendere solo dal comportamento
     * implicito del panel builder.
     *
     * Il superadmin SaaS è escluso a priori, indipendentemente dalle
     * aziende a cui risulta assegnato: gestisce aziende e gestori, ma non
     * deve mai poter leggere il contenuto delle segnalazioni di nessuna
     * azienda (nemmeno la propria, se ne gestisce una anche come gestore).
     */
    private function belongsToReportCompany(User $user, Report $report): bool
    {
        if ($user->is_superadmin) {
            return false;
        }

        return $user->companies()->where('companies.id', $report->company_id)->exists();
    }

    public function viewAny(User $user): bool
    {
        return (! $user->is_superadmin) && $user->companies()->exists();
    }

    public function view(User $user, Report $report): bool
    {
        return $this->belongsToReportCompany($user, $report);
    }

    public function create(User $user): bool
    {
        // Le segnalazioni arrivano solo dal form pubblico anonimo, mai create
        // manualmente dal pannello di gestione.
        return false;
    }

    public function update(User $user, Report $report): bool
    {
        return $this->belongsToReportCompany($user, $report);
    }

    public function delete(User $user, Report $report): bool
    {
        // Le segnalazioni non vanno mai cancellate per obbligo legale di
        // conservazione dell'audit trail.
        return false;
    }

    public function restore(User $user, Report $report): bool
    {
        return false;
    }

    public function forceDelete(User $user, Report $report): bool
    {
        return false;
    }
}
