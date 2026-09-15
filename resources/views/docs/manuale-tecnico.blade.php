<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manuale Operativo Tecnico — {{ config('app.name') }}</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #1f2933;
            --muted: #52606d;
            --border: #d9e2ec;
            --accent: #1d4ed8;
            --bg-soft: #f8fafc;
            --danger: #b91c1c;
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
        nav.toc ol { list-style: none; margin: 0; padding: 0; counter-reset: toc; }
        nav.toc li { counter-increment: toc; margin-bottom: 6px; }
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
        .callout.warn { border-left-color: var(--danger); }
        .callout strong { display: block; margin-bottom: 4px; }
        .badge { display: inline-block; padding: 1px 8px; border-radius: 8px; font-size: 11px; font-weight: 600; }
        .badge.new { background: #fde8e8; color: var(--danger); }
        .badge.progress { background: #fef3c7; color: #92400e; }
        .badge.closed { background: #d1fae5; color: #065f46; }
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
            <li><a href="#introduzione">Introduzione e scopo</a></li>
            <li><a href="#architettura">Architettura tecnica</a></li>
            <li><a href="#dati">Modello dati</a></li>
            <li><a href="#multitenancy">Multi-tenancy</a></li>
            <li><a href="#sicurezza">Sicurezza e protezione dati</a></li>
            <li><a href="#flusso-pubblico">Flusso pubblico del segnalante</a></li>
            <li><a href="#flusso-gestore">Flusso operativo del gestore</a></li>
            <li><a href="#scadenze">Scadenziario di legge automatizzato</a></li>
            <li><a href="#job">Job pianificati e comandi Artisan</a></li>
            <li><a href="#configurazione">Configurazione d'ambiente</a></li>
            <li><a href="#manutenzione">Manutenzione operativa</a></li>
        </ol>
    </nav>
    <main>
        <header class="cover">
            <div class="kicker">Documentazione tecnica</div>
            <h1>Manuale Operativo Tecnico</h1>
            <p class="subtitle">{{ config('app.name') }} — Piattaforma di whistleblowing conforme al D.Lgs. 24/2023 (attuazione della Direttiva UE 2019/1937)</p>
            <div class="meta">Documento riservato ad uso tecnico/amministrativo interno · Generato il {{ now()->format('d/m/Y H:i') }}</div>
        </header>

        <section id="introduzione">
            <h2>1. Introduzione e scopo</h2>
            <p>
                {{ config('app.name') }} è un'applicazione Laravel che implementa un canale di segnalazione interno
                (whistleblowing) ad uso multi-azienda: ogni azienda cliente (<em>tenant</em>) dispone di un proprio
                canale pubblico anonimo per la ricezione delle segnalazioni e di un pannello di gestione riservato
                ai gestori incaricati (<em>gestori delle segnalazioni</em>).
            </p>
            <p>
                Il sistema automatizza gli adempimenti previsti dalla normativa italiana sul whistleblowing: avviso
                di ricevimento entro 7 giorni, riscontro finale entro 3 mesi, conservazione limitata nel tempo dei
                dati con anonimizzazione automatica, e tracciabilità delle comunicazioni tra segnalante e gestore
                mantenendo l'anonimato del primo.
            </p>
        </section>

        <section id="architettura">
            <h2>2. Architettura tecnica</h2>
            <table>
                <thead><tr><th>Livello</th><th>Tecnologia</th><th>Note</th></tr></thead>
                <tbody>
                    <tr><td>Framework applicativo</td><td class="mono">Laravel 12 (PHP 8.4)</td><td>Struttura file standard Laravel 11+, configurazione middleware/eccezioni in <code>bootstrap/app.php</code>.</td></tr>
                    <tr><td>Pannello amministrativo</td><td class="mono">Filament v5</td><td>Risorse in <code>app/Filament/Resources</code>, provider in <code>app/Providers/Filament/AdminPanelProvider.php</code>.</td></tr>
                    <tr><td>Form pubblici</td><td class="mono">Livewire</td><td><code>app/Livewire/PublicReportForm.php</code> e <code>PublicReportTracker.php</code>, layout <code>layouts.guest</code>.</td></tr>
                    <tr><td>Allegati</td><td class="mono">Spatie Media Library</td><td>Collection <code>evidence</code> sul modello <code>Report</code>, disco <code>private</code>, contenuto cifrato.</td></tr>
                    <tr><td>Audit trail</td><td class="mono">Spatie Activitylog</td><td>Traccia solo i cambi di campi non sensibili (es. stato), mai il contenuto in chiaro.</td></tr>
                    <tr><td>PDF</td><td class="mono">barryvdh/laravel-dompdf</td><td>Esportazione del fascicolo segnalazione.</td></tr>
                    <tr><td>2FA</td><td class="mono">Filament Multi-Factor (TOTP)</td><td><code>Filament\Auth\MultiFactor\App\AppAuthentication</code>.</td></tr>
                </tbody>
            </table>
        </section>

        <section id="dati">
            <h2>3. Modello dati</h2>

            <h3>Company (azienda / tenant)</h3>
            <p>Tabella <code>companies</code>. Campi principali: <code>name</code>, <code>slug</code> (usato negli URL pubblici), <code>logo_path</code>, <code>brand_color</code> (white-label del pannello e delle pagine pubbliche), <code>shared_passcode</code> (codice opzionale condiviso per sbloccare il form pubblico) e <code>passcode_rotated_at</code>.</p>
            <div class="callout">
                <strong>Nota di sicurezza</strong>
                Il valore di <code>shared_passcode</code> non viene mai scritto nell'audit log: viene tracciata solo la data
                dell'ultima rotazione (<code>passcode_rotated_at</code>), aggiornata automaticamente tramite un mutatore
                sull'attributo quando il valore cambia realmente.
            </div>

            <h3>User (gestore)</h3>
            <p>Tabella <code>users</code>. Relazione <code>belongsToMany(Company)</code>: un gestore può essere assegnato a una o più aziende
            (multi-tenancy Filament tramite <code>HasTenants</code>). <code>canAccessPanel()</code> richiede almeno un'azienda assegnata.
            Autenticazione a due fattori TOTP tramite <code>InteractsWithAppAuthentication</code>.</p>

            <h3>Report (segnalazione)</h3>
            <table>
                <thead><tr><th>Campo</th><th>Tipo / cast</th><th>Note</th></tr></thead>
                <tbody>
                    <tr><td class="mono">tracking_token</td><td>string</td><td>PIN univoco nel formato <code>WHSL-XXXX-XXXX</code>, unico strumento con cui il segnalante accede in seguito alla propria pratica.</td></tr>
                    <tr><td class="mono">status</td><td>enum <code>ReportStatus</code></td><td><span class="badge new">Nuova</span> <span class="badge progress">In Lavorazione</span> <span class="badge closed">Chiusa</span></td></tr>
                    <tr><td class="mono">description</td><td>string, cast <code>encrypted</code></td><td>Contenuto cifrato a riposo nel database.</td></tr>
                    <tr><td class="mono">corrective_measures</td><td>string, cast <code>encrypted</code></td><td>Registro delle misure correttive adottate, cifrato.</td></tr>
                    <tr><td class="mono">acknowledgement_due_at</td><td>datetime</td><td>Impostata automaticamente a <code>created_at + 7 giorni</code> alla creazione.</td></tr>
                    <tr><td class="mono">feedback_due_at</td><td>datetime</td><td>Impostata automaticamente a <code>created_at + 3 mesi</code> alla creazione.</td></tr>
                    <tr><td class="mono">anonymized_at</td><td>datetime, nullable</td><td>Valorizzata da <code>anonymize()</code> quando la pratica supera il periodo di conservazione.</td></tr>
                </tbody>
            </table>
            <p>Gli allegati sono gestiti tramite la collection Media Library <code>evidence</code>, salvati sul disco <code>private</code> e cifrati a riposo (vedi sezione Sicurezza).</p>

            <h3>Message (messaggio di chat)</h3>
            <p>Tabella <code>messages</code>, relazione <code>hasMany</code> su <code>Report</code>. Campo <code>body</code> cifrato, <code>is_from_reporter</code> (booleano) distingue i messaggi del segnalante da quelli del gestore. Non sono modificabili né eliminabili una volta creati (integrità dell'audit trail), imposto a livello di <code>MessagePolicy</code>.</p>

            <h3>CorrectiveMeasureTemplate (modello di misura correttiva)</h3>
            <p>Tabella <code>corrective_measure_templates</code>. Campo <code>company_id</code> nullable: se <code>null</code> il modello è <em>globale</em> e visibile/riutilizzabile da tutte le aziende, altrimenti è specifico dell'azienda proprietaria. La modifica dei template globali è riservata alla console/seeder (vedi <code>CorrectiveMeasureTemplatePolicy</code>).</p>
        </section>

        <section id="multitenancy">
            <h2>4. Multi-tenancy</h2>
            <p>
                Il pannello Filament è configurato con <code>-&gt;tenant(Company::class, slugAttribute: 'slug')</code> in
                <code>AdminPanelProvider</code>: ogni URL del pannello è prefissato dallo slug dell'azienda selezionata e
                lo scoping automatico delle query per tenant è delegato a Filament, tranne dove esplicitamente
                disabilitato (es. <code>CorrectiveMeasureTemplateResource</code>, che ha bisogno di mostrare anche i
                modelli globali con <code>company_id</code> nullo, e ricostruisce lo scoping a mano in
                <code>getEloquentQuery()</code>).
            </p>
            <p>Il colore primario dell'interfaccia (<code>brand_color</code>) viene applicato dinamicamente in base al tenant corrente tramite la closure passata a <code>-&gt;colors()</code>.</p>
        </section>

        <section id="sicurezza">
            <h2>5. Sicurezza e protezione dati</h2>

            <h3>Autenticazione a due fattori</h3>
            <p>La 2FA tramite app authenticator (TOTP) è obbligatoria per ogni gestore al primo accesso utile, tranne in locale con <code>APP_DEBUG=true</code> dove resta disponibile ma non obbligatoria, per non intralciare lo sviluppo.</p>

            <h3>Cifratura dei dati</h3>
            <ul>
                <li><strong>Contenuto testuale:</strong> <code>description</code> e <code>corrective_measures</code> del Report, e <code>body</code> dei Message, sono cifrati a riposo tramite cast Eloquent <code>encrypted</code> (basato su <code>APP_KEY</code>).</li>
                <li><strong>Allegati:</strong> ogni file caricato dal segnalante viene cifrato con <code>Crypt::encryptString()</code> prima di essere scritto sul disco <code>private</code>; nome file originale e mime type sono conservati solo come custom properties del record Media (nel database), mai nel filesystem in chiaro.</li>
                <li><strong>Passcode condiviso:</strong> confrontato con <code>hash_equals()</code> per evitare timing attack; il valore non compare mai nei log applicativi.</li>
            </ul>

            <h3>Isolamento tenant e protezione IDOR</h3>
            <p>
                La rotta <code>/admin/media/{media}/download</code> verifica esplicitamente che l'utente autenticato
                appartenga alla company proprietaria del Report a cui il media è associato, prima di decifrare e
                restituire il file: previene l'accesso cross-tenant agli allegati anche conoscendo l'ID del media. La
                rotta di esportazione PDF (<code>/admin/reports/{report}/pdf</code>) applica la policy <code>view</code>
                del Report. Il tracciamento pubblico (<code>PublicReportTracker</code>) filtra sempre le segnalazioni con
                lo scope <code>forCompany()</code>, impedendo di raggiungere segnalazioni di un'altra azienda anche
                indovinando un PIN valido per un tenant diverso.
            </p>

            <h3>Rate limiting</h3>
            <table>
                <thead><tr><th>Azione</th><th>Chiave</th><th>Limite</th></tr></thead>
                <tbody>
                    <tr><td>Verifica passcode form pubblico</td><td class="mono">passcode-verify:{company}:{ip}</td><td>10 tentativi / 60s</td></tr>
                    <tr><td>Invio segnalazione</td><td class="mono">report-submit:{company}:{ip}</td><td>5 invii / 300s</td></tr>
                    <tr><td>Accesso tramite PIN</td><td class="mono">report-tracker:{company}:{ip}</td><td>10 tentativi / 60s</td></tr>
                </tbody>
            </table>

            <h3>Audit trail</h3>
            <p>
                Tramite Spatie Activitylog, <code>Company</code> e <code>Report</code> registrano solo le modifiche ai campi
                non sensibili (es. lo stato di una segnalazione, o la data di rotazione del passcode), mai il
                contenuto cifrato. Le voci di log sono consultabili dal pannello nella scheda "Log attività" della
                risorsa Segnalazioni.
            </p>

            <div class="callout warn">
                <strong>Politica di immodificabilità</strong>
                Le segnalazioni e i messaggi non possono essere creati né cancellati dal pannello amministrativo
                (arrivano esclusivamente dal form pubblico e non vengono mai eliminate, per obbligo di conservazione
                legale); i messaggi non sono modificabili una volta inviati. Queste regole sono imposte a livello di
                Policy (<code>ReportPolicy</code>, <code>MessagePolicy</code>), non solo di interfaccia.
            </div>
        </section>

        <section id="flusso-pubblico">
            <h2>6. Flusso pubblico del segnalante</h2>
            <ol>
                <li><strong>Accesso al canale:</strong> <code>/segnala/{company:slug}</code>. Se l'azienda ha impostato un <code>shared_passcode</code>, viene richiesto prima di mostrare il form.</li>
                <li><strong>Compilazione:</strong> oggetto, descrizione dettagliata, fino a 5 allegati (PDF, JPEG, PNG, MP3, WAV; max 10MB ciascuno) — inclusi eventuali messaggi vocali registrati e alterati nel timbro dal browser.</li>
                <li><strong>Invio:</strong> viene generato un PIN univoco <code>WHSL-XXXX-XXXX</code>, la segnalazione è salvata con stato "Nuova" e i gestori dell'azienda ricevono una notifica via email <em>priva del contenuto</em> della segnalazione (solo invito ad accedere al pannello).</li>
                <li><strong>Tracciamento:</strong> <code>/traccia/{company:slug}</code>, accesso tramite il PIN ricevuto. Il segnalante può leggere e inviare messaggi in chat con il gestore, restando anonimo, e può richiedere un incontro diretto (notifica ai gestori).</li>
            </ol>
        </section>

        <section id="flusso-gestore">
            <h2>7. Flusso operativo del gestore</h2>
            <ol>
                <li>Login sul pannello (<code>/admin</code>) con verifica 2FA TOTP.</li>
                <li>Selezione dell'azienda (tenant) se assegnato a più aziende.</li>
                <li>Dashboard con widget contatori (Nuove / In Lavorazione / Chiuse) scoperto per il tenant corrente.</li>
                <li>Risorsa <strong>Segnalazioni</strong>: elenco con colonne PIN, oggetto, stato, avviso/riscontro scaduti, incontro richiesto. Apertura di una pratica per: aggiornare lo stato, registrare le misure correttive (anche richiamando un <code>CorrectiveMeasureTemplate</code>), inviare l'avviso di ricevimento, esportare il fascicolo in PDF, rispondere in chat al segnalante.</li>
                <li>Risorsa <strong>Aziende</strong> (solo Amministrazione SaaS): anagrafica, logo, colore brand, generazione/rotazione del passcode condiviso, generazione del QR code del link pubblico di segnalazione.</li>
                <li>Risorsa <strong>Utenti</strong> (solo Amministrazione SaaS): creazione gestori e assegnazione alle aziende di competenza.</li>
                <li>Risorsa <strong>Modelli Misure Correttive</strong>: gestione dei testi predefiniti richiamabili nel registro delle misure correttive, globali o specifici per azienda.</li>
            </ol>
        </section>

        <section id="scadenze">
            <h2>8. Scadenziario di legge automatizzato</h2>
            <p>Alla creazione di ogni Report, il sistema calcola automaticamente due scadenze previste dal D.Lgs. 24/2023:</p>
            <table>
                <thead><tr><th>Adempimento</th><th>Termine</th><th>Campo</th></tr></thead>
                <tbody>
                    <tr><td>Avviso di ricevimento al segnalante</td><td>7 giorni dalla ricezione</td><td class="mono">acknowledgement_due_at</td></tr>
                    <tr><td>Riscontro finale sull'esito della segnalazione</td><td>3 mesi dalla ricezione</td><td class="mono">feedback_due_at</td></tr>
                </tbody>
            </table>
            <p>Le scadenze superate o in avvicinamento sono evidenziate nella tabella delle Segnalazioni e generano promemoria automatici ai gestori (vedi sezione successiva).</p>
        </section>

        <section id="job">
            <h2>9. Job pianificati e comandi Artisan</h2>
            <table>
                <thead><tr><th>Comando</th><th>Pianificazione</th><th>Funzione</th></tr></thead>
                <tbody>
                    <tr>
                        <td class="mono">reports:check-deadlines</td>
                        <td>Giornaliera</td>
                        <td>Invia promemoria ai gestori: avviso di ricevimento scaduto o in scadenza entro 2 giorni; riscontro finale scaduto o in scadenza entro 14 giorni. Evita duplicati tramite i timestamp <code>*_reminder_sent_at</code>.</td>
                    </tr>
                    <tr>
                        <td class="mono">reports:anonymize-expired</td>
                        <td>Giornaliera</td>
                        <td>Anonimizza le segnalazioni chiuse da più tempo del periodo di conservazione configurato (contenuto, allegati e messaggi vengono cancellati, mantenendo solo i metadati necessari a statistiche aggregate e audit).</td>
                    </tr>
                </tbody>
            </table>
            <p>La pianificazione è definita in <code>routes/console.php</code> tramite la facade <code>Schedule</code>; è necessario che lo scheduler Laravel (<code>php artisan schedule:run</code>) sia attivo via cron/supervisor in produzione.</p>
        </section>

        <section id="configurazione">
            <h2>10. Configurazione d'ambiente</h2>
            <table>
                <thead><tr><th>Variabile</th><th>Descrizione</th><th>Default</th></tr></thead>
                <tbody>
                    <tr><td class="mono">APP_NAME</td><td>Nome applicazione mostrato in email e pannello.</td><td class="mono">UnicoWhistle</td></tr>
                    <tr><td class="mono">REPORT_RETENTION_MONTHS</td><td>Mesi di conservazione di una segnalazione chiusa prima dell'anonimizzazione automatica.</td><td class="mono">24</td></tr>
                    <tr><td class="mono">APP_DEBUG</td><td>Se <code>true</code>, disabilita l'obbligo di 2FA (solo per sviluppo locale).</td><td class="mono">false in produzione</td></tr>
                </tbody>
            </table>
            <p>Il file <code>config/whistleblowing.php</code> centralizza le impostazioni specifiche di dominio (attualmente <code>retention_months</code>).</p>
        </section>

        <section id="manutenzione">
            <h2>11. Manutenzione operativa</h2>
            <ul>
                <li>Verificare periodicamente che lo scheduler (<code>schedule:run</code>) sia in esecuzione: da esso dipendono sia i promemoria di legge sia l'anonimizzazione automatica, entrambi requisiti di conformità.</li>
                <li>Il disco <code>private</code> deve restare non accessibile pubblicamente: gli allegati sono cifrati, ma il percorso di storage non deve comunque essere esposto da configurazioni del webserver.</li>
                <li>Eventuali interventi su <code>APP_KEY</code> (rotazione della chiave) rendono illeggibili i dati cifrati con la chiave precedente (descrizioni, misure correttive, messaggi, allegati): pianificare sempre una migrazione dei dati cifrati prima di sostituire la chiave.</li>
                <li>Il pannello richiede almeno un'azienda assegnata per consentire l'accesso a un utente (<code>canAccessPanel()</code>): un gestore senza aziende assegnate non può accedere, anche con credenziali valide.</li>
            </ul>
        </section>

        <footer class="print">
            Documento riservato ad uso tecnico interno. Non condividere al di fuori del team autorizzato alla gestione e manutenzione della piattaforma.
        </footer>
    </main>
</div>
</body>
</html>
