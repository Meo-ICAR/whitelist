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
        return $user->is_superadmin || $user->companies()->exists();
    }

    public function view(User $user, Company $company): bool
    {
        return $user->is_superadmin || $this->managesCompany($user, $company);
    }

    public function create(User $user): bool
    {
        // Un superadmin SaaS è l'unico ruolo abilitato a creare nuove
        // aziende (tenant): senza questa restrizione, qualunque gestore
        // aziendale potrebbe creare nuovi tenant dal proprio pannello.
        return $user->is_superadmin;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->is_superadmin || $this->managesCompany($user, $company);
    }

    public function delete(User $user, Company $company): bool
    {
        // Eliminare un'azienda cancella a cascata le sue segnalazioni:
        // operazione riservata al supporto, mai al pannello (nemmeno al
        // superadmin).
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
