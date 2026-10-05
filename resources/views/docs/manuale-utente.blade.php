<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Manuale Utente — {{ config('app.name') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2933; }
        h1 { font-size: 22px; margin-bottom: 2px; }
        h2 { font-size: 15px; margin-top: 26px; border-bottom: 1px solid #d9e2ec; padding-bottom: 4px; }
        h3 { font-size: 13px; margin-top: 16px; margin-bottom: 6px; }
        p { line-height: 1.5; }
        .cover-meta { color: #52606d; margin-bottom: 24px; font-size: 11px; }
        .lead { color: #52606d; font-size: 13px; margin-bottom: 16px; }
        ol, ul { padding-left: 18px; }
        li { margin-bottom: 5px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0 16px; }
        table th, table td { padding: 5px 6px; border-bottom: 1px solid #eee; text-align: left; vertical-align: top; font-size: 11px; }
        table thead th { background: #f8fafc; }
        .note {
            border: 1px solid #d9e2ec;
            border-left: 3px solid #1d4ed8;
            background: #f8fafc;
            padding: 8px 12px;
            margin: 10px 0 16px;
            font-size: 11px;
        }
        .note strong { display: block; margin-bottom: 2px; }
        .page-break { page-break-before: always; }
        .footer { margin-top: 30px; font-size: 9px; color: #9aa5b1; }
        .badge { display: inline-block; padding: 1px 7px; border-radius: 8px; font-size: 10px; }
        .badge-new { background: #fde8e8; color: #b91c1c; }
        .badge-progress { background: #fef3c7; color: #92400e; }
        .badge-closed { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <h1>Manuale Utente</h1>
    <p class="cover-meta">
        {{ config('app.name') }} &middot; Piattaforma di segnalazione (whistleblowing) &middot;
        Versione del {{ now()->format('d/m/Y') }}
    </p>
    <p class="lead">
        Questa guida spiega come utilizzare {{ config('app.name') }} sia dal punto di vista del gestore delle
        segnalazioni (pannello di amministrazione), sia dal punto di vista di chi effettua una segnalazione in
        forma anonima.
    </p>

    <h2>1. Primo accesso del gestore</h2>
    <ol>
        <li>Apri il link del pannello fornito dall'amministratore (indirizzo del tipo <em>https://tuo-dominio/admin</em>) e accedi con l'email e la password ricevute.</li>
        <li>Al primo accesso ti verrà chiesto di configurare l'autenticazione a due fattori: installa un'app authenticator (es. Google Authenticator, Microsoft Authenticator, Authy) sul tuo telefono, inquadra il codice QR mostrato a schermo e inserisci il codice a 6 cifre generato per confermare.</li>
        <li>Conserva in un luogo sicuro i codici di recupero mostrati durante la configurazione: ti servono se dovessi perdere l'accesso all'app authenticator.</li>
        <li>Se sei assegnato a più aziende, dal menu in alto puoi selezionare quella su cui vuoi operare in ogni momento.</li>
    </ol>
    <div class="note">
        <strong>Perché la 2FA è obbligatoria</strong>
        Il pannello dà accesso a dati potenzialmente molto sensibili sui segnalanti: la doppia autenticazione è un
        requisito di sicurezza non disattivabile dall'utente.
    </div>

    <h2>2. La dashboard</h2>
    <p>
        Alla schermata iniziale trovi un riepilogo delle segnalazioni dell'azienda selezionata, suddivise per stato:
        <span class="badge badge-new">Nuova</span>, <span class="badge badge-progress">In Lavorazione</span> e
        <span class="badge badge-closed">Chiusa</span>.
    </p>

    <h2>3. Gestire una segnalazione</h2>
    <h3>3.1 Elenco segnalazioni</h3>
    <p>
        Dal menu "Segnalazioni" vedi l'elenco delle pratiche ricevute, con PIN, oggetto, stato e le colonne che
        segnalano un avviso di ricevimento o un riscontro finale scaduti, oltre a eventuali richieste di incontro
        diretto da parte del segnalante. Puoi filtrare per stato o per "Scadenze superate".
    </p>

    <h3>3.2 Aprire e gestire una pratica</h3>
    <p>Cliccando su "Apri e Gestisci" accedi al dettaglio della segnalazione, dove puoi:</p>
    <ul>
        <li>Leggere oggetto, descrizione e allegati caricati dal segnalante;</li>
        <li>Consultare la sezione scadenze di legge (avviso di ricevimento entro 7 giorni, riscontro finale entro 3 mesi dalla ricezione), con evidenza delle scadenze superate;</li>
        <li>Inviare l'avviso di ricevimento al segnalante con l'apposita azione (richiede conferma);</li>
        <li>Aggiornare lo stato della pratica (Nuova / In Lavorazione / Chiusa);</li>
        <li>Registrare le misure correttive adottate, eventualmente richiamando un modello di testo predefinito dalla sezione "Modelli Misure Correttive";</li>
        <li>Rispondere ai messaggi del segnalante nella scheda "Messaggi", nella chat associata alla pratica;</li>
        <li>Consultare lo storico delle modifiche nella scheda "Log attività";</li>
        <li>Esportare il fascicolo completo della segnalazione in PDF con l'azione "Esporta PDF".</li>
    </ul>
    <div class="note">
        <strong>Cosa non si può fare, e perché</strong>
        Le segnalazioni non possono essere create manualmente dal pannello (arrivano solo dal modulo pubblico) né
        eliminate (obbligo di conservazione previsto dalla legge). I messaggi, una volta inviati, non sono
        modificabili né cancellabili: garantiscono la tracciabilità delle comunicazioni con il segnalante.
    </div>

    <h2>4. Gestire l'azienda (solo amministratori)</h2>
    <ul>
        <li><strong>Anagrafica e branding:</strong> nome, logo e colore del pannello e delle pagine pubbliche dell'azienda.</li>
        <li><strong>Codice di accesso condiviso (passcode):</strong> se impostato, viene richiesto ai segnalanti prima di poter compilare il modulo pubblico; può essere generato o ruotato in qualsiasi momento dalla scheda azienda.</li>
        <li><strong>QR code del canale di segnalazione:</strong> genera un codice QR che punta al link pubblico di segnalazione dell'azienda, utile per affiggerlo in bacheca o inserirlo in comunicazioni interne.</li>
    </ul>

    <h2>5. Gestire gli utenti gestori (solo amministratori)</h2>
    <p>
        Dal menu "Utenti" puoi creare nuovi account gestore e assegnare a ciascuno una o più aziende: un gestore
        vedrà e potrà operare solo sulle segnalazioni delle aziende a cui è stato assegnato.
    </p>

    <h2>6. Modelli di misure correttive</h2>
    <p>
        Dal menu "Modelli Misure Correttive" puoi creare testi predefiniti da richiamare rapidamente quando registri
        le misure correttive adottate su una pratica. Un modello può essere "globale" (visibile a tutte le aziende,
        gestito centralmente) oppure specifico della singola azienda.
    </p>

    <div class="page-break"></div>

    <h2>7. Il punto di vista del segnalante</h2>
    <p>
        Chi effettua una segnalazione non deve creare un account: utilizza il link pubblico dell'azienda,
        normalmente nella forma <em>https://tuo-dominio/segnala/nome-azienda</em>.
    </p>

    <h3>7.1 Inviare una segnalazione</h3>
    <ol>
        <li>Se richiesto, inserisci il codice di accesso condiviso fornito dall'azienda.</li>
        <li>Compila oggetto e descrizione dettagliata dei fatti. È possibile restare completamente anonimi: si consiglia di non inserire dati personali nel testo se si desidera l'anonimato.</li>
        <li>Puoi allegare fino a 5 file come prova (PDF, immagini JPEG/PNG, audio MP3/WAV), fino a 10 MB ciascuno.</li>
        <li>Al termine dell'invio ricevi un codice PIN univoco (es. <code>WHSL-A8F2-9K1M</code>): <strong>annotalo e conservalo</strong>, è l'unico modo per accedere in seguito alla tua segnalazione.</li>
    </ol>

    <h3>7.2 Seguire l'andamento della segnalazione</h3>
    <p>
        Dalla pagina di tracciamento dell'azienda (es. <em>https://tuo-dominio/traccia/nome-azienda</em>), inserendo
        il PIN ricevuto puoi:
    </p>
    <ul>
        <li>Leggere eventuali risposte del gestore incaricato;</li>
        <li>Inviare nuovi messaggi o chiarimenti, restando anonimo;</li>
        <li>Richiedere un incontro diretto con il gestore, se lo ritieni necessario.</li>
    </ul>
    <div class="note">
        <strong>Attenzione al PIN</strong>
        Il PIN non viene mai inviato via email né può essere recuperato in altro modo: se lo si perde, non è
        possibile risalire alla propria segnalazione.
    </div>

    <h2>8. Domande frequenti</h2>
    <table>
        <thead><tr><th>Domanda</th><th>Risposta</th></tr></thead>
        <tbody>
            <tr><td>Il gestore può risalire alla mia identità?</td><td>No, se non inserisci dati personali nella descrizione o nei messaggi: la piattaforma non richiede né registra alcun dato identificativo del segnalante.</td></tr>
            <tr><td>Cosa succede ai dati di una segnalazione chiusa?</td><td>Trascorso il periodo di conservazione previsto (configurato dall'azienda), il contenuto e gli allegati vengono cancellati automaticamente e la segnalazione viene anonimizzata, mantenendo solo i dati statistici aggregati.</td></tr>
            <tr><td>Entro quanto tempo ricevo una risposta?</td><td>L'avviso di ricevimento è previsto entro 7 giorni dalla ricezione; un riscontro sull'esito della segnalazione è previsto entro 3 mesi.</td></tr>
        </tbody>
    </table>

    <p class="footer">
        Documento generato automaticamente da {{ config('app.name') }}. Per assistenza contatta l'amministratore del tuo canale di segnalazione.
    </p>
</body>
</html>
