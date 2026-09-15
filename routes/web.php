<?php

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

Route::redirect('/', '/admin');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.welcome');
Route::get('/traccia/{company:slug}', PublicReportTracker::class)
    ->name('report.track');

Route::get('/admin/media/{media}/download', function (Request $request, Media $media) {
    // Sicurezza: il media appartiene a un Report, che appartiene a una Company.
    // Un gestore può scaricare solo gli allegati delle segnalazioni delle aziende
    // a cui è stato assegnato (impedisce l'IDOR cross-tenant sugli allegati).
    $report = $media->model;

    if (! ($report instanceof \App\Models\Report)) {
        throw new AccessDeniedHttpException;
    }

    if (! $request->user()->companies()->where('companies.id', $report->company_id)->exists()) {
        throw new AccessDeniedHttpException;
    }

    return response()->download($media->getPath(), $media->file_name);
})->middleware(['auth'])->name('media.download');
