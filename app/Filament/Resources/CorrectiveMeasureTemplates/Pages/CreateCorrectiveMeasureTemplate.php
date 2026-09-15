<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates\Pages;

use App\Filament\Resources\CorrectiveMeasureTemplates\CorrectiveMeasureTemplateResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateCorrectiveMeasureTemplate extends CreateRecord
{
    protected static string $resource = CorrectiveMeasureTemplateResource::class;

    // Con lo scoping automatico disabilitato (per poter mostrare anche i
    // modelli globali) dobbiamo assegnare noi il tenant: un modello creato
    // dal pannello appartiene sempre all'azienda corrente, mai globale
    // (i modelli globali sono gestiti solo da seeder/console).
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Filament::getTenant()->id;

        return $data;
    }
}
