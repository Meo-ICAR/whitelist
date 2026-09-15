<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CorrectiveMeasureTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nome del Modello')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->label('Testo della Misura Correttiva')
                    ->helperText('Questo testo potrà essere richiamato e inserito rapidamente nel Registro Misure Correttive di una segnalazione.')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }
}
