<?php
namespace App\Livewire;

use App\Models\Company;
use App\Models\Report;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Livewire\Component;

class PublicReportForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Company $company;
    public ?array $data = [];
    // Variabili per la schermata di successo
    public bool $isSubmitted = false;
    public string $trackingPin = '';

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Oggetto della segnalazione')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descrizione dettagliata dei fatti')
                    ->required()
                    ->rows(6)
                    ->helperText('Non inserire i tuoi dati personali se desideri rimanere anonimo.'),
                // Integrazione nativa con Spatie Media Library
                SpatieMediaLibraryFileUpload::make('attachments')
                    ->label('Allegati e Prove')
                    ->collection('evidence')  // Assicurati che il modello Report gestisca questa collection
                    ->multiple()
                    ->maxFiles(5)
                    ->maxSize(10240)  // 10MB
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'audio/mpeg'])
                    ->disk('private'),  // Fondamentale: usa un disco NON pubblico
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Genera un PIN univoco e facile da leggere (es. WHSL-A8F2-9K1M)
        $this->trackingPin = 'WHSL-' . strtoupper(Str::random(4) . '-' . Str::random(4));

        // Salva nel database associando all'azienda
        $report = $this->company->reports()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'tracking_token' => $this->trackingPin,
            'status' => 'new',
        ]);

        // Associa i file caricati al modello Report (Spatie Media Library)
        $this->form->model($report)->saveRelationships();

        // Mostra la schermata di successo
        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.public-report-form')->layout('layouts.guest');
        // Usa un layout minimale senza menu o header!
    }
}
