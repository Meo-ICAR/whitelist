<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manuale Webmaster — {{ config('app.name') }}</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #1f2933;
            --muted: #52606d;
            --border: #d9e2ec;
            --accent: #1d4ed8;
            --bg-soft: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--ink);
            margin: 0;
            padding: 0;
            background: #fff;
            line-height: 1.6;
        }
        .layout { display: flex; max-width: 1200px; margin: 0 auto; }
        nav.toc {
            width: 260px;
            flex-shrink: 0;
            padding: 32px 16px;
            position: sticky;
            top: 0;
            align-self: flex-start;
            max-height: 100vh;
            overflow-y: auto;
            border-right: 1px solid var(--border);
        }
        nav.toc h2 { font-size: 13px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); margin: 0 0 12px; }
        nav.toc ol { list-style: none; margin: 0; padding: 0; }
        nav.toc li { margin-bottom: 6px; }
        nav.toc a { color: var(--ink); text-decoration: none; font-size: 13.5px; }
        nav.toc a:hover { color: var(--accent); text-decoration: underline; }
        main { flex: 1; min-width: 0; padding: 40px 48px 96px; }
        header.cover { margin-bottom: 40px; }
        header.cover .kicker { color: var(--accent); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: .08em; }
        header.cover h1 { font-size: 30px; margin: 8px 0 4px; }
        header.cover p.subtitle { color: var(--muted); margin: 0 0 16px; font-size: 15px; }
        header.cover .meta { font-size: 12.5px; color: var(--muted); border-top: 1px solid var(--border); padding-top: 12px; }
        section { margin: 48px 0; scroll-margin-top: 24px; }
        section h2 { font-size: 21px; border-bottom: 2px solid var(--border); padding-bottom: 8px; margin-bottom: 16px; }
        section h3 { font-size: 16px; margin: 24px 0 8px; }
        p { margin: 0 0 12px; }
        ul, ol { margin: 0 0 12px; padding-left: 22px; }
        li { margin-bottom: 4px; }
        code, .mono { font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace; font-size: 12.5px; background: var(--bg-soft); padding: 1px 5px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0 20px; font-size: 13.5px; }
        th, td { text-align: left; padding: 8px 10px; border-bottom: 1px solid var(--border); vertical-align: top; }
        thead th { background: var(--bg-soft); font-size: 12px; text-transform: uppercase; letter-spacing: .03em; color: var(--muted); }
        .callout { border-left: 3px solid var(--accent); background: var(--bg-soft); padding: 12px 16px; border-radius: 0 6px 6px 0; margin: 16px 0; font-size: 13.5px; }
        .callout strong { display: block; margin-bottom: 4px; }
        pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 16px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 12.5px;
            line-height: 1.5;
            margin: 0 0 16px;
        }
        pre code { background: none; padding: 0; color: inherit; }
        .snippet-label { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); margin-bottom: 4px; }
        .btn-preview {
            display: inline-block;
            padding: 10px 20px;
            background: var(--accent);
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin: 4px 8px 4px 0;
        }
        footer.print { margin-top: 64px; padding-top: 16px; border-top: 1px solid var(--border); font-size: 12px; color: var(--muted); }
        @media (max-width: 900px) {
            .layout { flex-direction: column; }
            nav.toc { width: auto; position: static; max-height: none; border-right: none; border-bottom: 1px solid var(--border); }
        }
        @media print {
            nav.toc { display: none; }
            .layout { display: block; }
            main { padding: 0; }
        }
    </style>
</head>
<body>
<div class="layout">
    <nav class="toc">
        <h2>Indice</h2>
        <ol>
            <li><a href="#introduzione">Cos'è e a cosa serve</a></li>
            <li><a href="#dove-trovare">Dove trovare link, PIN e QR code</a></li>
            <li><a href="#link-sito">Pubblicare il link sul sito</a></li>
            <li><a href="#snippet">Codice pronto da incollare</a></li>
            <li><a href="#qrcode">Il QR code</a></li>
            <li><a href="#testi">Testi suggeriti per i visitatori</a></li>
            <li><a href="#pin">Come funziona il PIN (per rispondere alle domande)</a></li>
            <li><a href="#checklist">Checklist pubblicazione</a></li>
        </ol>
    </nav>
    <main>
        <header class="cover">
            <div class="kicker">Documentazione per il webmaster</div>
            <h1>Manuale Webmaster</h1>
            <p class="subtitle">Come collegare il canale di segnalazione {{ config('app.name') }} al sito web aziendale</p>
            <div class="meta">Ad uso di chi gestisce il sito web dell'azienda cliente · Generato il {{ now()->format('d/m/Y H:i') }}</div>
        </header>

        <section id="introduzione">
            <h2>1. Cos'è e a cosa serve</h2>
            <p>
                {{ config('app.name') }} fornisce a ogni azienda cliente un canale di segnalazione whistleblowing
                pubblico e anonimo, conforme al D.Lgs. 24/2023. Questo canale è già online e funzionante: il compito
                del webmaster è solo renderlo <strong>raggiungibile dal sito istituzionale dell'azienda</strong>
                (tipicamente da una voce di menu "Whistleblowing" o "Segnalazioni" e/o dalla pagina "Etica e
                Compliance"), così che dipendenti, fornitori e altri interlocutori possano trovarlo facilmente.
            </p>
            <p>Non è richiesta alcuna integrazione tecnica complessa: basta pubblicare un link (e opzionalmente un QR code) verso pagine già pronte e ospitate su questa piattaforma.</p>
        </section>

        <section id="dove-trovare">
            <h2>2. Dove trovare link, PIN e QR code</h2>
            <p>Tutti questi elementi sono generati automaticamente e recuperabili dal gestore aziendale nel pannello di amministrazione, sezione <strong>Aziende</strong>:</p>
            <ul>
                <li><strong>Link Segnalazioni:</strong> colonna cliccabile/copiabile nell'elenco aziende, porta direttamente al modulo pubblico di invio segnalazione.</li>
                <li><strong>Codice d'Accesso</strong> (opzionale): se l'azienda ha attivato un codice condiviso a protezione del modulo, è visibile e copiabile nella stessa riga.</li>
                <li><strong>QR Code:</strong> azione "Genera QR Code" sulla riga dell'azienda, apre un'anteprima e un link "Scarica PNG" (vedi sezione 5).</li>
            </ul>
            <div class="callout">
                <strong>Chi ti fornisce questi elementi</strong>
                Il webmaster normalmente non ha accesso diretto al pannello di gestione delle segnalazioni: è il
                referente aziendale (gestore) a fornire il link pubblico, l'eventuale codice d'accesso e il link di
                download del QR code (quest'ultimo, una volta ricevuto, il webmaster può riutilizzarlo in autonomia,
                vedi sezione 5).
            </div>
        </section>

        <section id="link-sito">
            <h2>3. Pubblicare il link sul sito</h2>
            <p>Il link pubblico ha sempre questa forma (lo <code>slug</code> è il nome breve univoco dell'azienda, fornito dal gestore):</p>
            <table>
                <thead><tr><th>Pagina</th><th>URL</th><th>Uso consigliato</th></tr></thead>
                <tbody>
                    <tr><td>Invia una segnalazione</td><td class="mono">https://tuo-dominio/segnala/nome-azienda</td><td>Link principale da mettere in evidenza (es. bottone "Segnala un illecito").</td></tr>
                    <tr><td>Traccia una segnalazione</td><td class="mono">https://tuo-dominio/traccia/nome-azienda</td><td>Link secondario per chi ha già un PIN e vuole seguire l'esito.</td></tr>
                    <tr><td>Guida per chi segnala</td><td class="mono">https://tuo-dominio/guida/nome-azienda</td><td>Consigliata come link principale: spiega entrambi i passaggi e rimanda alle due pagine sopra.</td></tr>
                </tbody>
            </table>
            <p>
                Sostituisci <code>tuo-dominio</code> e <code>nome-azienda</code> con i valori reali forniti dal
                gestore (li trova già completi e pronti da copiare nel pannello, vedi sezione precedente).
            </p>
        </section>

        <section id="snippet">
            <h2>4. Codice pronto da incollare</h2>
            <p>Esempio minimo (bottone singolo verso la guida, che è il punto di ingresso più completo):</p>
            <p class="snippet-label">HTML</p>
            <pre><code>&lt;a href="https://tuo-dominio/guida/nome-azienda"
   class="btn-whistleblowing"
   target="_blank" rel="noopener"&gt;
  Segnala un illecito
&lt;/a&gt;</code></pre>

            <p>Esempio con due pulsanti separati (invio diretto + tracciamento), utile in una pagina dedicata "Whistleblowing":</p>
            <p class="snippet-label">HTML</p>
            <pre><code>&lt;div class="whistleblowing-links"&gt;
  &lt;a href="https://tuo-dominio/segnala/nome-azienda" target="_blank" rel="noopener"&gt;
    Invia una segnalazione
  &lt;/a&gt;
  &lt;a href="https://tuo-dominio/traccia/nome-azienda" target="_blank" rel="noopener"&gt;
    Traccia la tua segnalazione
  &lt;/a&gt;
&lt;/div&gt;</code></pre>

            <p>Anteprima visiva (solo per riferimento, lo stile va adattato al sito aziendale):</p>
            <a class="btn-preview" href="#">Segnala un illecito</a>
            <a class="btn-preview" style="background:#374151" href="#">Traccia la tua segnalazione</a>

            <div class="callout">
                <strong><code>target="_blank"</code> è consigliato ma non obbligatorio</strong>
                Apre il canale di segnalazione in una nuova scheda, lasciando il sito aziendale aperto: utile perché
                chi segnala potrebbe voler tornare facilmente al sito. Non incide in alcun modo sulla sicurezza o
                sull'anonimato della segnalazione.
            </div>
        </section>

        <section id="qrcode">
            <h2>5. Il QR code</h2>
            <p>
                Il QR code è disponibile come <strong>immagine PNG scaricabile direttamente</strong>, tramite un
                link pubblico che il webmaster può usare in totale autonomia, senza dover richiedere ogni volta uno
                screenshot al gestore:
            </p>
            <table>
                <thead><tr><th>Cosa</th><th>URL</th></tr></thead>
                <tbody>
                    <tr><td>Download diretto del QR code (PNG)</td><td class="mono">https://tuo-dominio/qr/nome-azienda</td></tr>
                </tbody>
            </table>
            <p>
                Aprendo questo link il browser scarica automaticamente il file <code>qrcode-nome-azienda.png</code>:
                il webmaster può caricarlo nella libreria media del proprio sito/CMS come qualsiasi altra immagine,
                oppure usarlo direttamente come sorgente di un tag immagine:
            </p>
            <p class="snippet-label">HTML</p>
            <pre><code>&lt;img src="https://tuo-dominio/qr/nome-azienda"
     alt="QR code segnalazioni" width="200" height="200"&gt;</code></pre>
            <div class="callout">
                <strong>Perché questo link è sicuro da pubblicare</strong>
                Il QR code codifica esattamente lo stesso link pubblico di invio segnalazione descritto nella
                sezione 3: non contiene né espone alcun dato riservato, quindi può essere linkato o incorporato
                liberamente sul sito, anche senza passare ogni volta dal gestore.
            </div>
            <p>Usi tipici sul sito e fuori dal sito:</p>
            <ul>
                <li>Inserito nella pagina "Whistleblowing" del sito accanto al link testuale, per chi consulta il sito da smartphone in un contesto fisico (es. in azienda);</li>
                <li>Stampato su locandine/bacheche aziendali, cartellini identificativi, materiale informativo cartaceo (il PNG generato è ad alta risoluzione, 600×600px, adatto anche alla stampa);</li>
                <li>Incluso in comunicazioni interne (intranet, newsletter dipendenti).</li>
            </ul>
        </section>

        <section id="testi">
            <h2>6. Testi suggeriti per i visitatori del sito</h2>
            <p>Testo breve da affiancare al link/bottone in una pagina "Whistleblowing" o "Etica e Compliance":</p>
            <div class="callout">
                <strong>Esempio di testo</strong>
                "[Nome Azienda] mette a disposizione un canale di segnalazione riservato, conforme al D.Lgs.
                24/2023, per segnalare violazioni o condotte irregolari. È possibile segnalare in forma anonima:
                non è richiesta alcuna registrazione. Dopo l'invio riceverai un codice personale (PIN) per seguire
                l'andamento della tua segnalazione."
            </div>
            <p>Per istruzioni dettagliate passo-passo da linkare (invece di riscriverle sul sito aziendale), rimanda alla guida già pronta su questa piattaforma: <code>https://tuo-dominio/guida/nome-azienda</code>.</p>
        </section>

        <section id="pin">
            <h2>7. Come funziona il PIN (per rispondere alle domande dei visitatori)</h2>
            <p>Può essere utile che il webmaster conosca queste informazioni di base, per rispondere a eventuali domande di chi visita il sito:</p>
            <ul>
                <li>Dopo l'invio di una segnalazione viene generato un <strong>PIN univoco</strong> (es. <code>WHSL-A8F2-9K1M</code>), mostrato una sola volta a fine invio.</li>
                <li>Il PIN è l'<strong>unico</strong> modo per accedere in seguito alla propria segnalazione: non viene inviato via email, non è recuperabile in altro modo e non richiede alcuna registrazione.</li>
                <li>Con il PIN, dalla pagina di tracciamento, il segnalante può leggere le risposte del gestore, inviare nuovi messaggi e richiedere un incontro diretto.</li>
                <li>Il sistema non richiede né conserva alcun dato che possa identificare chi ha inviato la segnalazione.</li>
            </ul>
        </section>

        <section id="checklist">
            <h2>8. Checklist pubblicazione</h2>
            <ul>
                <li>☐ Ricevuto dal gestore aziendale: link pubblico e, se attivo, il codice d'accesso.</li>
                <li>☐ Aggiunta una voce di menu o una pagina dedicata (es. "Whistleblowing" / "Segnalazioni") sul sito.</li>
                <li>☐ Inserito il link (o i due link separati) con testo chiaro, visibile senza dover cercare.</li>
                <li>☐ Verificato che il link funzioni realmente aprendo la pagina in incognito, da desktop e da smartphone.</li>
                <li>☐ (Facoltativo) Scaricato e pubblicato il QR code dal link <code>/qr/nome-azienda</code>, anche in versione cartacea/stampata.</li>
            </ul>
        </section>

        <footer class="print">
            Documento ad uso del webmaster incaricato dall'azienda cliente per la pubblicazione del link sul sito istituzionale.
        </footer>
    </main>
</div>
</body>
</html>
