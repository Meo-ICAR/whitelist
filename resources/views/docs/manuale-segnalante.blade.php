<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->name }} — Come inviare e seguire una segnalazione</title>

    {{-- Foglio di stile statico (nessuna build: niente Vite/Node in produzione) --}}
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">

    @if($company->brand_color)
    <style>
        :root {
            --brand-color: {{ $company->brand_color }};
            --brand-color-light: {{ $company->brand_color }}22;
        }
        .btn-brand { background-color: var(--brand-color); color: #fff; }
        .btn-brand:hover { opacity: 0.9; }
        .border-brand { border-color: var(--brand-color); }
        .text-brand { color: var(--brand-color); }
        .bg-brand-light { background-color: var(--brand-color-light); }
        .accent-bar { background-color: var(--brand-color); }
    </style>
    @else
    <style>
        :root { --brand-color: #1d4ed8; }
        .btn-brand { background-color: #1d4ed8; color: #fff; }
        .btn-brand:hover { opacity: 0.9; }
        .border-brand { border-color: #1d4ed8; }
        .text-brand { color: #1d4ed8; }
        .bg-brand-light { background-color: #1d4ed822; }
        .accent-bar { background-color: #1d4ed8; }
    </style>
    @endif
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <div class="accent-bar h-1 w-full"></div>

    <header class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-4">
            @if($company->logo_path)
                <img src="{{ \Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="h-10 object-contain">
            @else
                <span class="text-xl font-bold text-gray-800">{{ $company->name }}</span>
            @endif
            <span class="text-gray-300">|</span>
            <span class="text-sm text-gray-500">Portale Whistleblowing</span>
        </div>
    </header>

    <main class="flex-1 py-10 px-4">
        <div class="max-w-3xl mx-auto">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Guida per chi segnala</h1>
                <p class="text-gray-500 max-w-xl mx-auto">
                    Questa guida spiega, passo per passo, come inviare una segnalazione a
                    <strong>{{ $company->name }}</strong> e come seguirne l'andamento in totale sicurezza e,
                    se lo desideri, in forma anonima.
                </p>
            </div>

            {{-- CTA rapide --}}
            <div class="grid sm:grid-cols-2 gap-4 mb-12">
                <a href="{{ route('report.welcome', $company->slug) }}"
                   class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all">
                    <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800">Invia una segnalazione</span>
                    <span class="text-xs text-gray-500 text-center mt-1">Apri una nuova pratica</span>
                </a>
                <a href="{{ route('report.track', $company->slug) }}"
                   class="group flex flex-col items-center p-6 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-brand transition-all">
                    <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800">Traccia la tua segnalazione</span>
                    <span class="text-xs text-gray-500 text-center mt-1">Inserisci il tuo PIN</span>
                </a>
            </div>

            {{-- Come inviare --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">1. Come inviare una segnalazione</h2>
                <ol class="space-y-4">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">1</span>
                        <p class="text-gray-600">
                            Apri il link pubblico del canale di segnalazione di {{ $company->name }}.
                            @if($company->shared_passcode)
                                Ti verrà chiesto un codice di accesso: te lo fornisce l'azienda (ad esempio via bacheca interna o comunicazione riservata).
                            @else
                                Il form è accessibile direttamente, senza codici.
                            @endif
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">2</span>
                        <p class="text-gray-600">
                            Descrivi i fatti nell'apposito campo, nel modo più chiaro e dettagliato possibile.
                            <strong>Se vuoi restare anonimo, non inserire il tuo nome o altri dati che potrebbero identificarti</strong>
                            né nell'oggetto né nella descrizione.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">3</span>
                        <p class="text-gray-600">
                            Se hai delle prove (documenti, foto, registrazioni audio), puoi allegarle: fino a 5 file,
                            in formato PDF, JPEG, PNG, MP3 o WAV, massimo 10&nbsp;MB ciascuno.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">4</span>
                        <p class="text-gray-600">
                            Invia la segnalazione. Riceverai subito un <strong>PIN univoco</strong> (es.
                            <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">WHSL-A8F2-9K1M</code>):
                            <strong>annotalo e conservalo con cura</strong>, perché è l'unico modo per accedere in
                            seguito alla tua pratica. Non viene inviato via email e non può essere recuperato se lo
                            perdi.
                        </p>
                    </li>
                </ol>
            </section>

            {{-- Cosa succede dopo --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">2. Cosa succede dopo l'invio</h2>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-brand flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Un gestore incaricato della gestione delle segnalazioni riceve un avviso e prende in carico la pratica, senza mai conoscere la tua identità se hai scelto di restare anonimo.</span>
                    </li>
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-brand flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Entro <strong>7 giorni</strong> dalla ricezione riceverai un avviso di ricevimento della segnalazione.</span>
                    </li>
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-brand flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Entro <strong>3 mesi</strong> riceverai un riscontro sull'esito o sullo stato della tua segnalazione.</span>
                    </li>
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-brand flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Tutte le comunicazioni con il gestore avvengono nella chat della tua pratica, accessibile con il PIN.</span>
                    </li>
                </ul>
            </section>

            {{-- Come tracciare --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">3. Come seguire l'andamento della tua segnalazione</h2>
                <ol class="space-y-4">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">1</span>
                        <p class="text-gray-600">
                            Apri la pagina "Traccia la tua segnalazione" e inserisci il PIN ricevuto al momento
                            dell'invio.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">2</span>
                        <p class="text-gray-600">
                            Potrai leggere le eventuali risposte del gestore e inviare nuovi messaggi o chiarimenti,
                            sempre restando anonimo.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-sm flex items-center justify-center">3</span>
                        <p class="text-gray-600">
                            Se lo ritieni utile, puoi richiedere un <strong>incontro diretto</strong> con il gestore
                            direttamente dalla pagina di tracciamento.
                        </p>
                    </li>
                </ol>
            </section>

            {{-- Sicurezza --}}
            <section class="bg-brand-light rounded-2xl p-8 mb-6 border border-brand/20">
                <h2 class="text-xl font-bold text-gray-900 mb-3">Sicurezza e anonimato</h2>
                <p class="text-gray-700 mb-2">
                    Il testo della tua segnalazione, i messaggi e gli eventuali allegati sono cifrati e non sono mai
                    leggibili in chiaro, nemmeno da chi ha accesso diretto al server. Non ti viene richiesto alcun
                    dato personale per inviare o tracciare una segnalazione.
                </p>
                <p class="text-gray-700">
                    La normativa (D.Lgs. 24/2023) vieta espressamente ogni forma di ritorsione nei confronti di chi
                    segnala in buona fede.
                </p>
            </section>

            {{-- FAQ --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Domande frequenti</h2>
                <div class="space-y-5">
                    <div>
                        <p class="font-semibold text-gray-800">Devo registrarmi o creare un account?</p>
                        <p class="text-gray-600 text-sm mt-1">No. Non serve nessuna registrazione: l'unico elemento necessario è il PIN che ricevi dopo l'invio.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Ho perso il PIN, come faccio?</p>
                        <p class="text-gray-600 text-sm mt-1">Purtroppo il PIN non può essere recuperato in nessun modo: per tutelare l'anonimato non viene conservato alcun collegamento tra la segnalazione e chi l'ha inviata.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Cosa succede ai miei dati nel tempo?</p>
                        <p class="text-gray-600 text-sm mt-1">Trascorso il periodo di conservazione previsto, il contenuto della segnalazione e gli allegati vengono cancellati automaticamente e la pratica viene anonimizzata.</p>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <footer class="py-4 text-center text-xs text-gray-400">
        Piattaforma conforme al D.Lgs. 24/2023 &mdash; Tutti i dati sono cifrati
    </footer>
</body>
</html>
