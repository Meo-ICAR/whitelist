<?php

use App\Livewire\PublicReportForm;
use App\Livewire\PublicReportTracker;
use App\Models\Company;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

Route::get('/', function () {
    return view('marketing.landing');
})->name('marketing.landing');

Route::get('/segnala/{company:slug}', PublicReportForm::class)
    ->name('report.welcome');
Route::get('/traccia/{company:slug}', PublicReportTracker::class)
    ->name('report.track');
Route::get('/guida/{company:slug}', function (Company $company) {
    return view('docs.manuale-segnalante', ['company' => $company]);
})->name('report.guide');

// Immagine QR pubblica (nessun dato riservato: codifica lo stesso link
// pubblico di /segnala/{slug}), pensata per il download autonomo da parte
// del webmaster aziendale, senza passare dal pannello di gestione.
Route::get('/qr/{company:slug}', function (Company $company) {
    return response($company->qrCodePng(), 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'attachment; filename="qrcode-'.$company->slug.'.png"',
    ]);
})->name('report.qrcode');

Route::get('/admin/media/{media}/download', function (Request $request, Media $media) {
    // Sicurezza: il media appartiene a un Report, che appartiene a una Company.
    // Un gestore può scaricare solo gli allegati delle segnalazioni delle aziende
    // a cui è stato assegnato (impedisce l'IDOR cross-tenant sugli allegati).
    $report = $media->model;

    if (! ($report instanceof Report)) {
        throw new AccessDeniedHttpException;
    }

    if (! $request->user()->companies()->where('companies.id', $report->company_id)->exists()) {
        throw new AccessDeniedHttpException;
    }

    // Gli allegati sono cifrati a riposo (vedi PublicReportForm): decifriamo
    // al volo qui, senza mai scrivere il contenuto in chiaro su disco, e
    // ripristiniamo nome/mime originali salvati come custom properties.
    if ($media->getCustomProperty('encrypted')) {
        $content = Crypt::decryptString(file_get_contents($media->getPath()));

        return response($content, 200, [
            'Content-Type' => $media->getCustomProperty('original_mime', 'application/octet-stream'),
            'Content-Disposition' => 'attachment; filename="'.addslashes($media->getCustomProperty('original_name', $media->file_name)).'"',
        ]);
    }

    return response()->download($media->getPath(), $media->file_name);
})->middleware(['auth'])->name('media.download');

Route::get('/admin/reports/{report}/pdf', function (Request $request, Report $report) {
    $request->user()->can('view', $report) || throw new AccessDeniedHttpException;

    $report->load(['company', 'messages']);

    $pdf = Pdf::loadView('pdf.report', ['report' => $report]);

    return $pdf->download("segnalazione-{$report->tracking_token}.pdf");
})->middleware(['auth'])->name('reports.pdf');

// I manuali non contengono dati riservati di alcun tenant (solo
// documentazione generica di prodotto): restano pubblici così da poter
// essere consultati/scaricati anche dalla welcome page commerciale, da
// chi richiede una demo e non ha ancora un account.
Route::get('/admin/docs/manuale-tecnico', function () {
    return view('docs.manuale-tecnico');
})->name('docs.manuale-tecnico');

Route::get('/admin/docs/manuale-webmaster', function () {
    return view('docs.manuale-webmaster');
})->name('docs.manuale-webmaster');

Route::get('/admin/docs/manuale-utente', function () {
    $pdf = Pdf::loadView('docs.manuale-utente');

    return $pdf->download('manuale-utente.pdf');
})->name('docs.manuale-utente');
