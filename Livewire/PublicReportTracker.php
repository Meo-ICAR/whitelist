<?php
namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;

class PublicReportTracker extends Component
{
    public string $pin = '';
    public ?Report $report = null;
    public string $newMessage = '';
    public string $errorMessage = '';

    // 1. Fase di "Login" tramite PIN
    public function accessReport()
    {
        $this->errorMessage = '';

        // Cerca la segnalazione tramite il tracking token univoco
        $report = Report::where('tracking_token', $this->pin)->first();

        if ($report) {
            $this->report = $report;
        } else {
            $this->errorMessage = 'PIN non valido o segnalazione inesistente.';
        }
    }

    // 2. Fase di invio messaggio
    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|min:2'
        ]);

        $this->report->messages()->create([
            'body' => $this->newMessage,
            'is_from_reporter' => true,  // Questo messaggio arriva dal front-end!
        ]);

        $this->newMessage = '';  // Pulisce l'input

        // Opzionale: Ricarica la relazione per aggiornare la vista
        $this->report->load('messages');
    }

    public function render()
    {
        return view('livewire.public-report-tracker')->layout('layouts.guest');
    }
}
