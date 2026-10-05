<?php

namespace App\Policies;

use App\Models\CorrectiveMeasureTemplate;
use App\Models\User;

class CorrectiveMeasureTemplatePolicy
{
    private function belongsToSameCompany(User $user, CorrectiveMeasureTemplate $template): bool
    {
        return $user->companies()->where('companies.id', $template->company_id)->exists();
    }

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, CorrectiveMeasureTemplate $template): bool
    {
        // I modelli globali (company_id nullo) sono richiamabili da
        // qualunque azienda, non solo da chi li possiede.
        if (is_null($template->company_id)) {
            return $user->companies()->exists();
        }

        return $this->belongsToSameCompany($user, $template);
    }

    public function create(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function update(User $user, CorrectiveMeasureTemplate $template): bool
    {
        // I modelli globali non sono modificabili da nessuna azienda: sono
        // gestiti solo da console/seeder.
        if (is_null($template->company_id)) {
            return false;
        }

        return $this->belongsToSameCompany($user, $template);
    }

    public function delete(User $user, CorrectiveMeasureTemplate $template): bool
    {
        if (is_null($template->company_id)) {
            return false;
        }

        return $this->belongsToSameCompany($user, $template);
    }

    public function restore(User $user, CorrectiveMeasureTemplate $template): bool
    {
        return false;
    }

    public function forceDelete(User $user, CorrectiveMeasureTemplate $template): bool
    {
        return false;
    }
}
