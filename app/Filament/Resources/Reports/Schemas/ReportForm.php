<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Enums\ReportStatus;
use App\Models\CorrectiveMeasureTemplate;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli della Segnalazione')
                    ->description('Il contenuto è crittografato nel database e visibile solo in questa schermata.')
                    ->schema([
                        TextInput::make('tracking_token')
                            ->label('PIN Pratica')
                            ->disabled()  // Disabilitato: non può essere modificato
                            ->columnSpan(1),
                        TextInput::make('created_at')
                            ->label('Data e Ora di Invio')
                            // Bugfix: lo stato del campo arriva come stringa (non
                            // un'istanza Carbon) quando il form idrata i valori
                            // dal record; Carbon::parse gestisce entrambi i casi.
                            ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d/m/Y H:i') : '')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('title')
                            ->label('Oggetto')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Descrizione dei Fatti')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                        // Gli allegati sono cifrati a riposo (contenuto non
                        // leggibile direttamente dal disco): il download
                        // passa sempre dalla route autenticata che decifra
                        // al volo, non dal componente FileUpload nativo.
                        Placeholder::make('attachments')
                            ->label('Prove / Allegati (cifrati a riposo)')
                            ->content(function ($record) {
                                if (! $record) {
                                    return '-';
                                }

                                $media = $record->getMedia('evidence');

                                if ($media->isEmpty()) {
                                    return 'Nessun allegato.';
                                }

                                return new HtmlString(
                                    $media->map(fn ($item) => sprintf(
                                        '<a href="%s" target="_blank" class="underline text-primary-600">%s</a>',
                                        route('media.download', $item),
                                        e($item->getCustomProperty('original_name', $item->file_name)),
                                    ))->implode('<br>')
                                );
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Scadenze di Legge')
                    ->description('Termini previsti dal D.Lgs. 24/2023.')
                    ->schema([
                        Placeholder::make('acknowledgement_status')
                            ->label('Avviso di Ricevimento (entro 7gg)')
                            ->content(function ($record) {
                                if (! $record) {
                                    return '-';
                                }

                                if ($record->acknowledged_at) {
                                    return 'Inviato il ' . $record->acknowledged_at->format('d/m/Y H:i');
                                }

                                $due = $record->acknowledgement_due_at;

                                return $due?->isPast()
                                    ? 'SCADUTO il ' . $due->format('d/m/Y')
                                    : 'Da inviare entro il ' . $due?->format('d/m/Y');
                            }),
                        Placeholder::make('feedback_status')
                            ->label('Riscontro Finale (entro 3 mesi)')
                            ->content(function ($record) {
                                if (! $record) {
                                    return '-';
                                }

                                if ($record->status === ReportStatus::Closed) {
                                    return 'Pratica chiusa.';
                                }

                                $due = $record->feedback_due_at;

                                return $due?->isPast()
                                    ? 'SCADUTO il ' . $due->format('d/m/Y')
                                    : 'Da fornire entro il ' . $due?->format('d/m/Y');
                            }),
                        Placeholder::make('meeting_requested')
                            ->label('Incontro Diretto')
                            ->content(fn ($record) => $record?->meeting_requested_at
                                ? 'Richiesto dal segnalante il ' . $record->meeting_requested_at->format('d/m/Y H:i')
                                : 'Nessuna richiesta.')
                            ->visible(fn ($record) => (bool) $record?->meeting_requested_at),
                    ])
                    ->columns(2),
                Section::make('Gestione Pratica')
                    ->schema([
                        Select::make('status')
                            ->label('Stato Segnalazione')
                            ->options(ReportStatus::class)
                            ->required()
                            ->native(false),
                        Select::make('corrective_measure_template')
                            ->label('Richiama un Modello')
                            ->helperText('Seleziona un modello predefinito per inserirlo nel registro qui sotto. Gestisci i modelli in "Modelli Misure Correttive".')
                            ->options(fn () => CorrectiveMeasureTemplate::query()
                                ->where(function ($query) {
                                    $query->whereNull('company_id')
                                        ->orWhere('company_id', Filament::getTenant()?->id);
                                })
                                ->orderByRaw('company_id is null') // aziendali prima, globali dopo
                                ->orderBy('title')
                                ->pluck('title', 'id'))
                            ->native(false)
                            ->live()
                            // Campo puramente di servizio: non deve mai essere
                            // salvato come attributo della Report.
                            ->dehydrated(false)
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                if (blank($state)) {
                                    return;
                                }

                                $template = CorrectiveMeasureTemplate::find($state);

                                if (! $template) {
                                    return;
                                }

                                $existing = trim((string) $get('corrective_measures'));

                                $set('corrective_measures', $existing !== ''
                                    ? $existing . "\n\n" . $template->content
                                    : $template->content);
                            })
                            ->columnSpanFull(),
                        Textarea::make('corrective_measures')
                            ->label('Misure Correttive Intraprese')
                            ->helperText('Registro riservato delle azioni adottate a seguito della segnalazione (tutela anti-ritorsione).')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
