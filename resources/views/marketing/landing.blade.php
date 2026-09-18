<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Piattaforma di Whistleblowing</title>
    <meta name="description" content="Portale di segnalazione whistleblowing white-label, conforme al D.Lgs. 24/2023. Prova la demo con l'azienda dimostrativa Acme Srl.">

    <style>
        :root {
            --brand-color: #0f766e;
            --brand-color-light: #0f766e1a;
            --gray-900: #111827;
            --gray-800: #1f2937;
            --gray-500: #6b7280;
            --gray-400: #9ca3af;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-50: #f9fafb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
        }
        .accent-bar { height: 4px; background: var(--brand-color); }
        a { text-decoration: none; }
        header.site {
            background: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }
        .container {
            max-width: 1024px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }
        header.site .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .brand-name { font-size: 1.25rem; font-weight: 800; color: var(--gray-800); }
        .link-brand { color: var(--brand-color); font-weight: 600; font-size: .9rem; }
        .link-brand:hover { text-decoration: underline; }

        section { padding: 4rem 1.25rem; }
        .section-white { background: #fff; }
        .section-hero { background: #fff; border-bottom: 1px solid var(--gray-100); text-align: center; }
        .hero-inner { max-width: 640px; margin: 0 auto; }
        .badge {
            display: inline-block;
            background: var(--brand-color-light);
            color: var(--brand-color);
            font-size: .75rem;
            font-weight: 700;
            padding: .3rem .8rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }
        h1 { font-size: 2.25rem; font-weight: 800; color: var(--gray-900); margin: 0 0 1rem; }
        h2 { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); text-align: center; margin: 0 0 .5rem; }
        .lead { color: var(--gray-500); max-width: 640px; margin: 0 auto 2rem; }
        .lead-narrow { color: var(--gray-500); text-align: center; max-width: 560px; margin: 0 auto 2.5rem; }

        .btn {
            display: inline-block;
            font-weight: 700;
            padding: .8rem 1.5rem;
            border-radius: .75rem;
            font-size: .95rem;
        }
        .btn-brand { background: var(--brand-color); color: #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, .06); }
        .btn-brand:hover { opacity: .9; }
        .btn-outline { border: 2px solid var(--gray-200); color: var(--gray-800); }
        .btn-outline:hover { border-color: var(--brand-color); }
        .btn-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 1rem; }

        .grid { display: grid; gap: 1.5rem; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }

        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--gray-100);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
        }
        .card h3 { font-weight: 700; color: var(--gray-800); margin: 0 0 .5rem; font-size: 1rem; }
        .card p { font-size: .9rem; color: var(--gray-500); margin: 0; }
        code { background: var(--gray-100); border-radius: .25rem; padding: .1rem .35rem; font-size: .85em; }

        .section-demo { text-align: center; }
        .demo-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: .25rem;
            padding: 1.5rem;
            background: #fff;
            border: 2px solid var(--gray-100);
            border-radius: 1rem;
            transition: border-color .15s;
        }
        .demo-link:hover { border-color: var(--brand-color); }
        .demo-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            background: var(--brand-color-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .5rem;
            font-size: 1.4rem;
        }
        .demo-title { font-weight: 700; color: var(--gray-800); }
        .demo-sub { font-size: .75rem; color: var(--gray-500); }

        .panel {
            text-align: left;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 1rem;
            padding: 1.5rem;
        }
        .panel h3 { font-weight: 700; color: var(--gray-800); margin: 0 0 .75rem; }
        .panel p { font-size: .9rem; color: var(--gray-500); margin: 0 0 1rem; }
        .creds { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .75rem; margin-bottom: 1rem; }
        .creds dt { font-size: .7rem; color: var(--gray-400); text-transform: uppercase; letter-spacing: .04em; }
        .creds dd { margin: .15rem 0 0; font-family: monospace; color: var(--gray-800); }
        .creds > div { background: #fff; border: 1px solid var(--gray-100); border-radius: .75rem; padding: .75rem 1rem; }

        footer.site {
            text-align: center;
            font-size: .75rem;
            color: var(--gray-400);
            border-top: 1px solid var(--gray-100);
            background: #fff;
            padding: 1.5rem 1.25rem;
        }
    </style>
</head>
<body>

    <div class="accent-bar"></div>

    <header class="site">
        <div class="container">
            <span class="brand-name">{{ config('app.name') }}</span>
            <a href="{{ route('filament.admin.auth.login') }}" class="link-brand">
                Accedi al pannello gestori
            </a>
        </div>
    </header>

    <main>

        {{-- Hero --}}
        <section class="section-hero">
            <div class="hero-inner">
                <span class="badge">Conforme al D.Lgs. 24/2023 e alla Direttiva UE 2019/1937</span>
                <h1>Whistleblowing white-label, su misura per la tua azienda</h1>
                <p class="lead">
                    {{ config('app.name') }} è la piattaforma che offre alla tua azienda un portale di
                    segnalazione dedicato, cifrato e conforme, con un pannello di gestione riservato per i tuoi
                    responsabili compliance.
                </p>
                <div class="btn-row">
                    <a href="#demo-acme" class="btn btn-brand">Prova la demo con Acme Srl</a>
                    <a href="mailto:info@unicowhistle.demo?subject=Richiesta%20demo%20UnicoWhistle" class="btn btn-outline">
                        Richiedi una demo personalizzata
                    </a>
                </div>
            </div>
        </section>

        {{-- Caratteristiche commerciali --}}
        <section>
            <div class="container">
                <h2>Cosa include la piattaforma</h2>
                <p class="lead-narrow">
                    Tutto quello che serve per attivare un canale di segnalazione a norma in pochi minuti.
                </p>
                <div class="grid grid-3">

                    <div class="card">
                        <h3>Accesso riservato, dati isolati</h3>
                        <p>Solo i responsabili compliance autorizzati accedono al pannello di gestione delle
                            segnalazioni della tua azienda.</p>
                    </div>

                    <div class="card">
                        <h3>White-label</h3>
                        <p>Logo, colore aziendale e indirizzo dedicato (es. <code>/segnala/acme</code>) personalizzano
                            il portale e il pannello con il tuo brand.</p>
                    </div>

                    <div class="card">
                        <h3>Segnalazione anonima, senza account</h3>
                        <p>Il dipendente invia titolo, descrizione e allegati senza registrarsi e riceve un PIN
                            univoco per seguire la pratica.</p>
                    </div>

                    <div class="card">
                        <h3>Crittografia end-to-end</h3>
                        <p>Descrizione della segnalazione e messaggi sono cifrati a riposo; gli allegati sono
                            salvati su disco privato, mai accessibili via URL diretto.</p>
                    </div>

                    <div class="card">
                        <h3>Chat bidirezionale e audit trail</h3>
                        <p>Segnalante e gestore comunicano tramite il PIN, con cronologia messaggi immutabile:
                            nessuna modifica o cancellazione consentita.</p>
                    </div>

                    <div class="card">
                        <h3>QR code e passcode opzionale</h3>
                        <p>La tua azienda ottiene un QR code pronto da distribuire ai dipendenti, con la possibilità
                            di proteggere il form con un codice di accesso condiviso.</p>
                    </div>

                </div>
            </div>
        </section>

        {{-- Demo Acme Srl --}}
        <section id="demo-acme" class="section-white section-demo">
            <div class="container" style="max-width: 640px;">
                <span class="badge">Azienda demo</span>
                <h2>Naviga la demo di Acme Srl</h2>
                <p class="lead">
                    Acme Srl è un'azienda dimostrativa già configurata, con branding, una segnalazione e una
                    conversazione di esempio. Usala per esplorare il portale del dipendente e il pannello del
                    gestore senza dover creare nulla.
                </p>

                <div class="grid grid-2" style="margin-bottom: 2rem;">
                    <a href="{{ route('report.welcome', 'acme') }}" class="demo-link">
                        <span class="demo-icon">📝</span>
                        <span class="demo-title">Invia una segnalazione</span>
                        <span class="demo-sub">Portale pubblico del dipendente, senza passcode</span>
                    </a>

                    <a href="{{ route('report.track', 'acme') }}" class="demo-link">
                        <span class="demo-icon">🔍</span>
                        <span class="demo-title">Traccia una segnalazione</span>
                        <span class="demo-sub">Usa il PIN demo <code>WHSL-ACME-DEMO</code></span>
                    </a>

                    <a href="{{ route('report.guide', 'acme') }}" class="demo-link">
                        <span class="demo-icon">📖</span>
                        <span class="demo-title">Guida per il segnalante</span>
                        <span class="demo-sub">Come funziona il portale, passo per passo</span>
                    </a>

                    <a href="{{ route('report.qrcode', 'acme') }}" class="demo-link">
                        <span class="demo-icon">▦</span>
                        <span class="demo-title">Scarica il QR code</span>
                        <span class="demo-sub">Il codice da distribuire ai dipendenti Acme</span>
                    </a>
                </div>

                <div class="panel">
                    <h3>Accesso demo al pannello gestore</h3>
                    <p>
                        Accedi con queste credenziali dimostrative per esplorare la dashboard, la gestione della
                        segnalazione e la chat con il segnalante dal lato azienda.
                    </p>
                    <dl class="creds">
                        <div>
                            <dt>Email</dt>
                            <dd>demo@acme-demo.test</dd>
                        </div>
                        <div>
                            <dt>Password</dt>
                            <dd>demo12345</dd>
                        </div>
                    </dl>
                    <a href="{{ route('filament.admin.auth.login') }}" class="btn btn-brand">
                        Accedi al pannello gestori
                    </a>
                    <p style="margin: 1rem 0 0; font-size: .8rem; color: var(--gray-400);">
                        Una volta dentro, la voce di menu <strong>Test Pratico</strong> guida passo passo:
                        configura l'azienda, invia una segnalazione di prova e vai a vedere il reclamo ricevuto.
                    </p>
                </div>
            </div>
        </section>

        {{-- Documentazione scaricabile --}}
        <section class="section-demo">
            <div class="container" style="max-width: 780px;">
                <h2>Manuali e documentazione</h2>
                <p class="lead-narrow">
                    Consulta o scarica i manuali della piattaforma, già pronti così come li riceverebbe un
                    cliente reale: nessun account richiesto.
                </p>

                <div class="grid grid-2">
                    <a href="{{ route('report.guide', 'acme') }}" class="demo-link">
                        <span class="demo-icon">📖</span>
                        <span class="demo-title">Manuale del segnalante</span>
                        <span class="demo-sub">Guida per il dipendente, con branding Acme Srl</span>
                    </a>

                    <a href="{{ route('docs.manuale-utente') }}" class="demo-link">
                        <span class="demo-icon">📄</span>
                        <span class="demo-title">Manuale utente (PDF)</span>
                        <span class="demo-sub">Guida del gestore all'uso del pannello — download diretto</span>
                    </a>

                    <a href="{{ route('docs.manuale-webmaster') }}" class="demo-link" target="_blank" rel="noopener">
                        <span class="demo-icon">🌐</span>
                        <span class="demo-title">Manuale webmaster</span>
                        <span class="demo-sub">Come integrare i link e il QR code sul sito aziendale</span>
                    </a>

                    <a href="{{ route('docs.manuale-tecnico') }}" class="demo-link" target="_blank" rel="noopener">
                        <span class="demo-icon">⚙️</span>
                        <span class="demo-title">Manuale operativo tecnico</span>
                        <span class="demo-sub">Architettura, sicurezza e conformità della piattaforma</span>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <footer class="site">
        {{ config('app.name') }} &mdash; Piattaforma conforme al D.Lgs. 24/2023. Tutti i dati sono cifrati.
        Acme Srl è un'azienda dimostrativa a scopo di test.
    </footer>

</body>
</html>
