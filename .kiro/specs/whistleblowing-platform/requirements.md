# Requirements: Whistleblowing Platform

## Introduzione

Questo documento definisce i requisiti funzionali e non funzionali della piattaforma SaaS di whistleblowing, derivati dal design tecnico. La piattaforma deve essere conforme alla Direttiva UE 2019/1937 sul whistleblowing, garantendo anonimato del segnalante, crittografia dei dati sensibili e audit trail immutabile.

---

## Requisiti

### Requisito 1: Form Pubblico Anonimo

**User Story**: Come dipendente di un'azienda cliente, voglio poter inviare una segnalazione anonima tramite un URL dedicato alla mia azienda, senza dover creare un account.

#### Criteri di Accettazione

1. **Dato** un URL `/segnala/{slug}` con slug valido, **quando** il dipendente accede, **allora** il sistema mostra il form di segnalazione con il branding dell'azienda (logo e brand color).

2. **Dato** un URL `/segnala/{slug}` con slug inesistente, **quando** il dipendente accede, **allora** il sistema restituisce una pagina 404.

3. **Dato** un form di segnalazione, **quando** il dipendente compila titolo e descrizione e invia, **allora** il sistema crea un Report con `status = 'new'` associato alla Company corretta.

4. **Dato** un report appena creato, **quando** viene salvato nel database, **allora** il campo `description` è cifrato e non leggibile in chiaro nel DB.

5. **Dato** un invio riuscito, **quando** il report viene creato, **allora** il sistema genera e mostra un PIN di tracciamento nel formato `WHSL-XXXX-XXXX` (dove X è un carattere alfanumerico maiuscolo).

6. **Dato** un PIN generato, **quando** viene salvato come `tracking_token`, **allora** è univoco tra tutti i report nel database.

7. **Dato** il form di segnalazione, **quando** il dipendente allega file, **allora** i file vengono salvati sul disco `private` (non accessibile pubblicamente) tramite Spatie Media Library nella collection `evidence`.

8. **Dato** il form di segnalazione, **quando** il dipendente tenta di allegare più di 5 file o un file superiore a 10MB, **allora** il sistema mostra un errore di validazione.

---

### Requisito 2: Passcode di Sblocco Form

**User Story**: Come amministratore SaaS, voglio poter proteggere il form di segnalazione di un'azienda con un codice condiviso, in modo che solo i dipendenti di quella azienda possano accedervi.

#### Criteri di Accettazione

1. **Dato** un'azienda con `shared_passcode` impostato, **quando** un dipendente accede all'URL di segnalazione, **allora** il sistema mostra prima uno step di verifica passcode prima del form.

2. **Dato** lo step di verifica passcode, **quando** il dipendente inserisce il passcode corretto, **allora** il sistema sblocca e mostra il form di segnalazione.

3. **Dato** lo step di verifica passcode, **quando** il dipendente inserisce un passcode errato, **allora** il sistema mostra un errore e non sblocca il form.

4. **Dato** un'azienda senza `shared_passcode` (null o vuoto), **quando** un dipendente accede all'URL di segnalazione, **allora** il form è direttamente accessibile senza step di verifica.

---

### Requisito 3: Tracciamento Segnalazione via PIN

**User Story**: Come segnalante anonimo, voglio poter verificare lo stato della mia segnalazione e comunicare con il gestore usando il PIN ricevuto, senza dover rivelare la mia identità.

#### Criteri di Accettazione

1. **Dato** l'URL `/traccia/{slug}`, **quando** il segnalante inserisce un PIN valido, **allora** il sistema mostra il titolo, lo stato corrente e la cronologia messaggi della segnalazione.

2. **Dato** l'URL `/traccia/{slug}`, **quando** il segnalante inserisce un PIN inesistente, **allora** il sistema mostra il messaggio "PIN non valido o segnalazione inesistente".

3. **Dato** una segnalazione trovata tramite PIN, **quando** il segnalante invia un messaggio, **allora** il sistema crea un Message con `is_from_reporter = true` e `body` cifrato.

4. **Dato** la pagina di tracciamento, **quando** vengono mostrati i messaggi, **allora** sono ordinati per data (più recenti in basso o in alto in modo consistente).

---

### Requisito 4: Pannello Admin Multi-Tenant

**User Story**: Come gestore aziendale, voglio accedere a un pannello admin che mostri solo le segnalazioni della mia azienda, senza poter vedere i dati di altre aziende.

#### Criteri di Accettazione

1. **Dato** un gestore assegnato a una sola Company, **quando** effettua il login al pannello `/admin`, **allora** il sistema lo porta direttamente alla dashboard della sua azienda.

2. **Dato** un gestore assegnato a più Company, **quando** effettua il login al pannello `/admin`, **allora** il sistema mostra una pagina di selezione del tenant.

3. **Dato** un gestore autenticato nel pannello, **quando** visualizza la lista segnalazioni, **allora** vede solo i report con `company_id` corrispondente al tenant selezionato.

4. **Dato** un gestore del tenant A, **quando** tenta di accedere direttamente a un report del tenant B tramite URL, **allora** il sistema restituisce un errore 403 o 404.

5. **Dato** un gestore non assegnato ad alcuna Company, **quando** tenta di accedere al pannello, **allora** il sistema non mostra dati e lo reindirizza alla selezione tenant vuota.

---

### Requisito 5: Gestione Segnalazioni (Admin)

**User Story**: Come gestore aziendale, voglio poter aprire una segnalazione, leggerne il contenuto, aggiornarne lo stato e comunicare con il segnalante anonimo.

#### Criteri di Accettazione

1. **Dato** la lista segnalazioni, **quando** il gestore la visualizza, **allora** ogni riga mostra PIN, oggetto, stato (con badge colorato) e data di ricezione.

2. **Dato** una segnalazione con `status = 'new'`, **quando** il gestore la apre, **allora** può leggere titolo e descrizione in chiaro (Laravel decifra automaticamente) e scaricare gli allegati.

3. **Dato** la pagina di gestione di una segnalazione, **quando** il gestore modifica lo stato, **allora** il sistema aggiorna il campo `status` con uno dei valori: `new`, `in_progress`, `closed`.

4. **Dato** la pagina di gestione di una segnalazione, **quando** il gestore visualizza la sezione messaggi, **allora** vede la cronologia completa con mittente (Segnalante/Gestore), data e testo.

5. **Dato** la sezione messaggi, **quando** il gestore invia una risposta, **allora** il sistema crea un Message con `is_from_reporter = false` e `body` cifrato.

6. **Dato** un messaggio già inviato, **quando** il gestore visualizza la lista messaggi, **allora** non sono presenti azioni di modifica o cancellazione (audit trail immutabile).

7. **Dato** la lista segnalazioni, **quando** il gestore seleziona più record, **allora** non è disponibile alcuna azione di cancellazione in massa.

8. **Dato** il pannello admin, **quando** il gestore tenta di creare manualmente una segnalazione, **allora** l'azione non è disponibile (`canCreate = false`).

---

### Requisito 6: White-Label per Aziende Clienti

**User Story**: Come amministratore SaaS, voglio poter configurare il branding di ogni azienda cliente (logo, colore, slug) in modo che il loro portale di segnalazione abbia un aspetto personalizzato.

#### Criteri di Accettazione

1. **Dato** il form di creazione/modifica azienda, **quando** l'admin inserisce il nome, **allora** lo slug viene auto-generato in formato URL-safe (es. "Acme Corp" → "acme-corp").

2. **Dato** il form azienda, **quando** l'admin carica un logo, **allora** il file viene salvato nella cartella `company-logos` con visibilità pubblica.

3. **Dato** il form azienda, **quando** l'admin seleziona un brand color, **allora** il colore viene salvato come valore HEX e applicato come colore primario del pannello Filament per quel tenant.

4. **Dato** il pannello Filament con multi-tenancy attivo, **quando** un gestore è loggato nel tenant di una Company, **allora** il colore primario del pannello corrisponde al `brand_color` di quella Company.

5. **Dato** la lista aziende, **quando** l'admin visualizza una riga, **allora** può copiare lo slug (link segnalazioni) e il codice di accesso con un click.

---

### Requisito 7: QR Code per Link Segnalazione

**User Story**: Come amministratore SaaS, voglio poter generare un QR code per il link di segnalazione di ogni azienda, da consegnare ai dipendenti.

#### Criteri di Accettazione

1. **Dato** la lista aziende nel pannello admin, **quando** l'admin clicca "Genera QR Code" per un'azienda, **allora** il sistema mostra una modal con il QR code che punta all'URL `/segnala/{slug}`.

2. **Dato** il QR code generato, **quando** viene scansionato, **allora** reindirizza all'URL corretto del form di segnalazione dell'azienda.

3. **Dato** la modal del QR code, **quando** viene aperta, **allora** mostra il nome dell'azienda e il QR code in formato SVG di dimensione 250x250px.

---

### Requisito 8: Gestione Gestori (User Management)

**User Story**: Come amministratore SaaS, voglio poter creare account per i gestori aziendali e assegnarli alle aziende di competenza.

#### Criteri di Accettazione

1. **Dato** il form di creazione gestore, **quando** l'admin compila nome, email e password, **allora** il sistema crea un User con password hashata.

2. **Dato** il form di creazione/modifica gestore, **quando** l'admin seleziona le aziende, **allora** il sistema aggiorna la tabella pivot `company_user` con le associazioni corrette.

3. **Dato** un gestore esistente, **quando** l'admin modifica il form lasciando il campo password vuoto, **allora** la password non viene modificata.

4. **Dato** un gestore assegnato a più aziende, **quando** accede al pannello, **allora** può selezionare tra le aziende a lui assegnate tramite il tenant switcher di Filament.

---

### Requisito 9: Sicurezza e Conformità

**User Story**: Come responsabile compliance, voglio che la piattaforma garantisca la protezione dei dati sensibili e la conformità alla normativa sul whistleblowing.

#### Criteri di Accettazione

1. **Dato** qualsiasi report salvato nel database, **quando** si legge il valore raw della colonna `description`, **allora** il valore è cifrato e non corrisponde al testo originale.

2. **Dato** qualsiasi messaggio salvato nel database, **quando** si legge il valore raw della colonna `body`, **allora** il valore è cifrato e non corrisponde al testo originale.

3. **Dato** un allegato caricato da un segnalante, **quando** si tenta di accedere al file tramite URL pubblico diretto, **allora** il server restituisce un errore (file su disco privato).

4. **Dato** la piattaforma in produzione, **quando** un gestore del tenant A è autenticato, **allora** non può accedere a nessun dato (report, messaggi, media) del tenant B, indipendentemente dall'URL.

5. **Dato** il pannello admin, **quando** vengono visualizzati i messaggi di una segnalazione, **allora** non sono presenti pulsanti o azioni per modificare o eliminare i messaggi esistenti.
