<?php
namespace App\Livewire;

use App\Models\Company;
use App\Models\Report;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
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
    // Variabili per la verifica passcode
    public bool $passcodeVerified = false;
    public string $passcodeInput = '';

    public function mount(Company $company): void
    {
        $this->company = $company;
        // Se non c'è passcode, il form è direttamente accessibile
        if (empty($company->shared_passcode)) {
            $this->passcodeVerified = true;
        }
        $this->form->fill();
    }

    public function verifyPasscode(): void
    {
        if (empty($this->company->shared_passcode)) {
            $this->passcodeVerified = true;
            return;
        }

        if ($this->passcodeInput === $this->company->shared_passcode) {
            $this->passcodeVerified = true;
        } else {
            $this->addError('passcodeInput', 'Codice non valido');
        }
    }

    public function form(Schema $form): Schema
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
        $showPasscodeStep = !empty($this->company->shared_passcode) && !$this->passcodeVerified;

        return view('livewire.public-report-form', compact('showPasscodeStep'))
            ->layout('layouts.guest', ['company' => $this->company]);
    }
}
