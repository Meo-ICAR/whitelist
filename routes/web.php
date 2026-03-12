<?php

use App\Livewire\PublicReportForm;  // Il componente Livewire creato in precedenza
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.form');
Route::get('/tracker', \App\Livewire\PublicReportTracker::class)->name('report.tracker');
