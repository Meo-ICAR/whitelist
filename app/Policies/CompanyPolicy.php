<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    private function managesCompany(User $user, Company $company): bool
    {
        return $user->companies()->where('companies.id', $company->id)->exists();
    }

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Company $company): bool
    {
        return $this->managesCompany($user, $company);
    }

    public function create(User $user): bool
    {
        // Non esiste un ruolo "super-admin SaaS" distinto: senza questa
        // restrizione, qualunque gestore aziendale potrebbe creare nuove
        // aziende (tenant) dal proprio pannello. La creazione dei tenant è
        // riservata a operazioni fuori dal pannello (seeder/console/support).
        return false;
    }

    public function update(User $user, Company $company): bool
    {
        return $this->managesCompany($user, $company);
    }

    public function delete(User $user, Company $company): bool
    {
        // Eliminare un'azienda cancella a cascata le sue segnalazioni:
        // operazione riservata al supporto, mai a un gestore da pannello.
        return false;
    }

    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}
