<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Notifications\ManagerWelcome;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // La password in chiaro appena digitata dall'admin è disponibile solo
    // qui: $this->data contiene ancora il valore grezzo del form (il cast
    // ad hash avviene solo nella proiezione restituita da getState(), non
    // modifica $this->data), dopodiché nel modello resta solo l'hash e non
    // sarà più recuperabile.
    protected function afterCreate(): void
    {
        $plainPassword = $this->data['password'] ?? null;

        if (filled($plainPassword)) {
            $this->record->notify(new ManagerWelcome($this->record, $plainPassword));
        }
    }
}
