<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Data retention: anonimizza quotidianamente le segnalazioni chiuse scadute.
Schedule::command('reports:anonymize-expired')->daily();

// Scadenziario legale D.Lgs. 24/2023: promemoria giornaliero ai gestori.
Schedule::command('reports:check-deadlines')->daily();
