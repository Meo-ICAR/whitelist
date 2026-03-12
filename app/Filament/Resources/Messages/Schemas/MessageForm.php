<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('body')
                    ->label('Il tuo messaggio per il segnalante')
                    ->placeholder('Scrivi qui la tua risposta o richiedi ulteriori chiarimenti. Il segnalante leggerà questo messaggio accedendo con il suo PIN.')
                    ->required()
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }
}
