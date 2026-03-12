<?php

use App\Livewire\PublicCompanyWelcome;
use App\Livewire\PublicReportForm;  // Il componente Livewire creato in precedenza
use App\Livewire\ReportTracker;
use Illuminate\Support\Facades\Route;

// 1. Pagina di Benvenuto (Landing)
Route::get('/segnalazioni/{company:slug}', PublicCompanyWelcome::class)
    ->name('report.welcome');

// 2. Form di inserimento
Route::get('/segnalazioni/{company:slug}/nuova', PublicReportForm::class)
    ->name('report.form');

// 3. Verifica stato (Tracker)
Route::get('/segnalazioni/{company:slug}/traccia', ReportTracker::class)
    ->name('report.tracker');

Route::redirect('/', '/admin');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.form');
Route::get('/tracker', \App\Livewire\PublicReportTracker::class)->name('report.tracker');
