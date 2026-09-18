<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Piattaforma di Whistleblowing Multi-Azienda</title>
    <meta name="description" content="Portale di segnalazione whistleblowing white-label, multi-azienda, conforme al D.Lgs. 24/2023. Prova la demo con l'azienda dimostrativa Acme Srl.">

    @vite(['resources/css/app.css'])

    <style>
        :root { --brand-color: #0f766e; --brand-color-light: #0f766e22; }
        .btn-brand { background-color: var(--brand-color); color: #fff; }
        .btn-brand:hover { opacity: 0.9; }
        .border-brand { border-color: var(--brand-color); }
        .text-brand { color: var(--brand-color); }
        .bg-brand-light { background-color: var(--brand-color-light); }
        .accent-bar { background-color: var(--brand-color); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <div class="accent-bar h-1 w-full"></div>

    <header class="bg-white shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <span class="text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
            <a href="{{ route('filament.admin.auth.login') }}" class="text-sm font-medium text-brand hover:underline">
                Accedi al pannello gestori
            </a>
        </div>
    </header>

    <main class="flex-1">

        {{-- Hero --}}
        <section class="py-16 px-4 bg-white border-b border-gray-100">
            <div class="max-w-3xl mx-auto text-center">
                <span class="inline-block bg-brand-light text-brand text-xs font-semibold px-3 py-1 rounded-full mb-4">
                    Conforme al D.Lgs. 24/2023 e alla Direttiva UE 2019/1937
                </span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Whistleblowing white-label, un portale per ogni azienda cliente
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto mb-8">
                    {{ config('app.name') }} è la piattaforma SaaS multi-tenant che dà a ogni azienda cliente un
                    portale di segnalazione dedicato, cifrato e conforme, con un pannello di gestione isolato
                    per i suoi responsabili compliance.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="#demo-acme" class="btn-brand font-bold px-6 py-3 rounded-xl shadow-sm">
                        Prova la demo con Acme Srl
                    </a>
                    <a href="mailto:info@unicowhistle.demo?subject=Richiesta%20demo%20UnicoWhistle"
                       class="font-bold px-6 py-3 rounded-xl border-2 border-gray-200 text-gray-700 hover:border-brand">
                        Richiedi una demo personalizzata
                    </a>
                </div>
            </div>
        </section>

        {{-- Caratteristiche commerciali --}}
        <section class="py-16 px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-2">Cosa include la piattaforma</h2>
                <p class="text-gray-500 text-center max-w-2xl mx-auto mb-10">
                    Tutto quello che serve per attivare un canale di segnalazione a norma in pochi minuti,
                    per un numero illimitato di aziende clienti.
                </p>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">Multi-azienda, dati isolati</h3>
                        <p class="text-sm text-gray-500">
                            Ogni azienda cliente ha il proprio tenant: un gestore vede solo le segnalazioni della
                            propria azienda, mai quelle di altri clienti.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">White-label per ogni cliente</h3>
                        <p class="text-sm text-gray-500">
                            Logo, colore aziendale e slug dedicato (es. <code class="text-xs">/segnala/acme</code>)
                            personalizzano il portale e il pannello di ogni azienda.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">Segnalazione anonima, senza account</h3>
                        <p class="text-sm text-gray-500">
                            Il dipendente invia titolo, descrizione e allegati senza registrarsi e riceve un PIN
                            univoco per seguire la pratica.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">Crittografia end-to-end</h3>
                        <p class="text-sm text-gray-500">
                            Descrizione della segnalazione e messaggi sono cifrati a riposo; gli allegati sono
                            salvati su disco privato, mai accessibili via URL diretto.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">Chat bidirezionale e audit trail</h3>
                        <p class="text-sm text-gray-500">
                            Segnalante e gestore comunicano tramite il PIN, con cronologia messaggi immutabile:
                            nessuna modifica o cancellazione consentita.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">QR code e passcode opzionale</h3>
                        <p class="text-sm text-gray-500">
                            Ogni azienda ottiene un QR code pronto da distribuire ai dipendenti, con la possibilità
                            di proteggere il form con un codice di accesso condiviso.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- Demo Acme Srl --}}
        <section id="demo-acme" class="py-16 px-4 bg-white border-t border-gray-100">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-10">
                    <span class="inline-block bg-brand-light text-brand text-xs font-semibold px-3 py-1 rounded-full mb-4">
                        Azienda demo
                    </span>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Naviga la demo di Acme Srl</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">
                        Acme Srl è un'azienda dimostrativa già configurata, con branding, una segnalazione e una
                        conversazione di esempio. Usala per esplorare il portale del dipendente e il pannello del
                        gestore senza dover creare nulla.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    <a href="{{ route('report.welcome', 'acme') }}"
                       class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all text-center">
                        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800">Invia una segnalazione</span>
                        <span class="text-xs text-gray-500 mt-1">Portale pubblico del dipendente, senza passcode</span>
                    </a>

                    <a href="{{ route('report.track', 'acme') }}"
                       class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all text-center">
                        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800">Traccia una segnalazione</span>
                        <span class="text-xs text-gray-500 mt-1">Usa il PIN demo <code class="font-mono">WHSL-ACME-DEMO</code></span>
                    </a>

                    <a href="{{ route('report.guide', 'acme') }}"
                       class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all text-center">
                        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800">Guida per il segnalante</span>
                        <span class="text-xs text-gray-500 mt-1">Come funziona il portale, passo per passo</span>
                    </a>

                    <a href="{{ route('report.qrcode', 'acme') }}"
                       class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all text-center">
                        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 4h4v4h-4V4zM4 4h4v4H4V4zm0 12h4v4H4v-4z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800">Scarica il QR code</span>
                        <span class="text-xs text-gray-500 mt-1">Il codice da distribuire ai dipendenti Acme</span>
                    </a>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                    <h3 class="font-bold text-gray-800 mb-3">Accesso demo al pannello gestore</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Accedi con queste credenziali dimostrative per esplorare la dashboard, la gestione della
                        segnalazione e la chat con il segnalante dal lato azienda.
                    </p>
                    <dl class="grid sm:grid-cols-2 gap-3 text-sm mb-4">
                        <div class="bg-white border border-gray-100 rounded-xl px-4 py-3">
                            <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                            <dd class="font-mono text-gray-800">demo@acme-demo.test</dd>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-xl px-4 py-3">
                            <dt class="text-xs text-gray-400 uppercase tracking-wide">Password</dt>
                            <dd class="font-mono text-gray-800">demo12345</dd>
                        </div>
                    </dl>
                    <a href="{{ route('filament.admin.auth.login') }}" class="btn-brand inline-block font-bold px-5 py-2.5 rounded-xl shadow-sm">
                        Accedi al pannello gestori
                    </a>
                </div>
            </div>
        </section>

    </main>

    <footer class="py-6 text-center text-xs text-gray-400 border-t border-gray-100 bg-white">
        {{ config('app.name') }} &mdash; Piattaforma conforme al D.Lgs. 24/2023. Tutti i dati sono cifrati.
        Acme Srl è un'azienda dimostrativa a scopo di test.
    </footer>

</body>
</html>
