<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates\Pages;

use App\Filament\Resources\CorrectiveMeasureTemplates\CorrectiveMeasureTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCorrectiveMeasureTemplates extends ListRecords
{
    protected static string $resource = CorrectiveMeasureTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
