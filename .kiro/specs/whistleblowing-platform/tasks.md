# Tasks: Whistleblowing Platform

## Panoramica

Questo task list copre sia le funzionalità già implementate (da verificare/consolidare) sia quelle commentate o mancanti da completare. L'ordine riflette le dipendenze tecniche.

---

- [x] 1. Completare il modello Report con Media Collection
  - [x] 1.1 Aggiungere il metodo `registerMediaCollections()` al modello `Report` con collection `evidence` su disco `private`
  - [x] 1.2 Verificare che il cast `encrypted` su `description` sia correttamente configurato
  - [x] 1.3 Aggiungere scope `forCompany(Company $company)` al modello Report per il tenant filtering

- [x] 2. Implementare il Passcode di Sblocco Form (PublicReportForm)
  - [x] 2.1 Aggiungere proprietà `$passcodeVerified` e `$passcodeInput` al componente Livewire `PublicReportForm`
  - [x] 2.2 Implementare il metodo `verifyPasscode()` che confronta l'input con `company->shared_passcode`
  - [x] 2.3 Aggiornare il metodo `render()` per mostrare lo step passcode se `shared_passcode` è impostato e non ancora verificato
  - [x] 2.4 Creare la view Blade per lo step di verifica passcode (`livewire/public-report-form.blade.php`)

- [x] 3. Configurare le Route Pubbliche
  - [x] 3.1 Aggiungere route `/segnala/{company:slug}` per la welcome page / passcode step
  - [x] 3.2 Aggiungere route `/traccia/{company:slug}` per il PublicReportTracker
  - [x] 3.3 Verificare che il route model binding via `slug` funzioni correttamente per Company

- [x] 4. Attivare Multi-Tenancy Filament
  - [x] 4.1 Decommentare `->tenant(Company::class, slugAttribute: 'slug')` in `AdminPanelProvider`
  - [x] 4.2 Decommentare `->tenantMenu(true)` per abilitare il selettore tenant
  - [x] 4.3 Aggiungere il metodo `canAccessPanel(Panel $panel): bool` al modello `User` (verifica che abbia almeno una Company assegnata)
  - [x] 4.4 Verificare che `Company` implementi `HasTenants` o che Filament gestisca automaticamente la relazione `BelongsToMany`

- [x] 5. Attivare Brand Color Dinamico per Tenant
  - [x] 5.1 Decommentare il blocco `->colors([...])` in `AdminPanelProvider` con la closure che legge `Filament::getTenant()?->brand_color`
  - [x] 5.2 Verificare che il fallback `#1d4ed8` sia applicato quando il tenant non ha brand color impostato

- [x] 6. Registrare MessagesRelationManager in ReportResource
  - [x] 6.1 Aggiungere `MessagesRelationManager::class` all'array restituito da `getRelations()` in `ReportResource`
  - [x] 6.2 Verificare che il form del MessagesRelationManager includa solo il campo `body` (Textarea)
  - [x] 6.3 Testare che `mutateFormDataUsing` forzi correttamente `is_from_reporter = false`

- [x] 7. Creare le View Blade Pubbliche
  - [x] 7.1 Creare il layout `layouts/guest.blade.php` con supporto brand color dinamico (CSS variable o inline style)
  - [x] 7.2 Creare la view `livewire/public-report-form.blade.php` con step passcode e step form
  - [x] 7.3 Creare la view `livewire/public-report-tracker.blade.php` con input PIN, stato segnalazione e chat
  - [x] 7.4 Applicare il brand color dell'azienda alle view pubbliche tramite CSS inline o variabile

- [x] 8. Completare CompanyResource con QR Code e Link
  - [x] 8.1 Verificare che l'azione `generate_qr` in `CompaniesTable` usi la route corretta `route('report.welcome', ['company' => $record->slug])`
  - [x] 8.2 Aggiornare la colonna `slug` nella tabella per mostrare l'URL completo (non solo lo slug)
  - [x] 8.3 Decommentare `->revealable()` sul campo `shared_passcode` in `CompanyForm`

- [x] 9. Configurare il Disco Privato per gli Allegati
  - [x] 9.1 Verificare la configurazione del disco `private` in `config/filesystems.php`
  - [x] 9.2 Aggiungere una route autenticata per il download degli allegati (es. `/admin/media/{media}/download`)
  - [x] 9.3 Verificare che `SpatieMediaLibraryFileUpload` in `ReportForm` usi la collection `evidence` (attualmente usa `reports` — allineare con il form pubblico)

- [x] 10. Aggiungere Navigazione e Gruppi nel Pannello Admin
  - [x] 10.1 Decommentare `$navigationGroup = 'Amministrazione SaaS'` in `CompanyResource` e `UserResource`
  - [x] 10.2 Nascondere `MessageResource` dalla navigazione (già impostato, verificare)
  - [x] 10.3 Aggiungere widget alla Dashboard: contatore segnalazioni per stato (new/in_progress/closed)

- [x] 11. Scrivere i Test
  - [x] 11.1 Test unitario: generazione PIN — verifica formato `WHSL-[A-Z0-9]{4}-[A-Z0-9]{4}` e unicità
  - [x] 11.2 Test unitario: verifica passcode — casi null, corretto, errato
  - [x] 11.3 Test di integrazione: flusso completo invio segnalazione → PIN → tracciamento → chat
  - [x] 11.4 Test di sicurezza: verifica che `description` e `body` siano cifrati nel DB
  - [x] 11.5 Test multi-tenancy: gestore A non vede report di Company B
  - [x] 11.6 Test: `canCreate()` restituisce `false` per ReportResource
  - [x] 11.7 Test: messaggi non hanno azioni edit/delete disponibili

- [x] 12. Seeder e Dati di Test
  - [x] 12.1 Aggiornare `DatabaseSeeder` con una Company di esempio, un User gestore e un Report di test
  - [x] 12.2 Aggiungere factory per `Company`, `Report` e `Message`
