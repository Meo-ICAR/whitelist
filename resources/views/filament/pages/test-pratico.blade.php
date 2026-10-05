<x-filament-panels::page>

    <x-filament::section
        heading="Prova pratica in 3 passi"
        description="Segui questi passaggi per simulare l'intera esperienza di un cliente: configura l'azienda, invia una segnalazione come se fossi un dipendente, poi torna qui a gestirla come gestore."
    >
        <div class="grid gap-4 fi-sections-grid" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">

            <div>
                <h3 class="text-sm font-semibold">1. Configura l'azienda</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Personalizza logo, colore aziendale, codice di accesso ed email del webmaster di
                    <strong>{{ $company->name }}</strong>.
                </p>
                <div class="mt-3">
                    <x-filament::button tag="a" :href="$editCompanyUrl" icon="heroicon-o-building-office-2">
                        Configura {{ $company->name }}
                    </x-filament::button>
                </div>
                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                    @if($company->webmaster_email)
                        Email webmaster impostata: <span class="font-mono">{{ $company->webmaster_email }}</span> —
                        usa "Invia info al Webmaster" in alto per inviarle il link pubblico, il codice d'accesso, il
                        QR code e gli snippet HTML della pagina personalizzata con lo slug <span class="font-mono">{{ $company->slug }}</span>.
                    @else
                        Nessuna email webmaster impostata: aggiungila nel form qui sopra per poter inviare il link,
                        il QR code e gli snippet della pagina personalizzata con lo slug <span class="font-mono">{{ $company->slug }}</span>.
                    @endif
                </p>
            </div>

            <div>
                <h3 class="text-sm font-semibold">2. Invia una segnalazione di test</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Apri il portale pubblico come farebbe un dipendente, invia una segnalazione e conserva il PIN mostrato a fine invio.
                </p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <x-filament::button tag="a" :href="$publicFormUrl" target="_blank" icon="heroicon-o-pencil-square">
                        Invia una segnalazione
                    </x-filament::button>
                    <x-filament::button tag="a" :href="$guideUrl" target="_blank" color="gray" icon="heroicon-o-book-open">
                        Guida per il segnalante
                    </x-filament::button>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold">3. Visualizza la segnalazione ricevuta</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Torna nel pannello di gestione: la segnalazione appena inviata è già in lista, pronta per essere aperta, gestita e per rispondere al segnalante.
                </p>
                <div class="mt-3">
                    <x-filament::button tag="a" :href="$reportsUrl" icon="heroicon-o-shield-exclamation">
                        Vai alle segnalazioni ricevute
                    </x-filament::button>
                </div>
            </div>

        </div>
    </x-filament::section>

    <x-filament::section
        heading="Tracciamento lato segnalante"
        description="Facoltativo: verifica anche l'esperienza del dipendente che segue l'esito della propria segnalazione con il PIN ricevuto."
    >
        <x-filament::button tag="a" :href="$trackerUrl" target="_blank" color="gray" icon="heroicon-o-magnifying-glass">
            Traccia una segnalazione con il PIN
        </x-filament::button>
    </x-filament::section>

</x-filament-panels::page>
