<?php

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

Route::redirect('/', '/admin');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.welcome');
Route::get('/traccia/{company:slug}', PublicReportTracker::class)
    ->name('report.track');

Route::get('/admin/media/{media}/download', function (Media $media) {
    return response()->download($media->getPath(), $media->file_name);
})->middleware(['auth'])->name('media.download');
