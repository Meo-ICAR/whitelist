<?php

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.form');
Route::get('/tracker', PublicReportTracker::class)->name('report.tracker');
