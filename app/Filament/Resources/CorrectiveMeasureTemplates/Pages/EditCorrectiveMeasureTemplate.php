<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates\Pages;

use App\Filament\Resources\CorrectiveMeasureTemplates\CorrectiveMeasureTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCorrectiveMeasureTemplate extends EditRecord
{
    protected static string $resource = CorrectiveMeasureTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
