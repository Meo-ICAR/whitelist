<div>
    @if ($showPasscodeStep)
        {{-- ── Step 1: Verifica passcode ── --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="accent-bar h-1 w-full"></div>
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Accesso Riservato</h2>
                        <p class="text-sm text-gray-500">Inserisci il codice aziendale per accedere al form</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="passcode" class="block text-sm font-medium text-gray-700 mb-1">
                            Codice di accesso
                        </label>
                        <input
                            id="passcode"
                            type="password"
                            wire:model="passcodeInput"
                            wire:keydown.enter="verifyPasscode"
                            placeholder="Inserisci il codice aziendale"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition"
                            style="focus-ring-color: var(--brand-color)"
                            autocomplete="off"
                        >
                        @error('passcodeInput')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        wire:click="verifyPasscode"
                        wire:loading.attr="disabled"
                        class="btn-brand w-full py-2.5 px-4 rounded-lg font-semibold text-sm transition cursor-pointer disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="verifyPasscode">Accedi al form</span>
                        <span wire:loading wire:target="verifyPasscode">Verifica in corso...</span>
                    </button>
                </div>

                <p class="mt-6 text-xs text-gray-400 text-center">
                    Il codice ti è stato fornito dalla tua azienda. Contatta il responsabile compliance se non lo possiedi.
                </p>
            </div>
        </div>

    @elseif ($isSubmitted)
        {{-- ── Step 3: Conferma con PIN ── --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="accent-bar h-1 w-full"></div>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Segnalazione inviata</h2>
                <p class="text-gray-500 mb-8">
                    La tua segnalazione è stata ricevuta in modo sicuro. Conserva il PIN qui sotto per monitorare lo stato e comunicare con il gestore.
                </p>

                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-6 mb-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Il tuo PIN di tracciamento</p>
                    <p class="text-3xl font-mono font-bold text-gray-900 tracking-widest">{{ $trackingPin }}</p>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-left mb-6">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-amber-700">
                            <strong>Importante:</strong> Questo PIN non verrà mostrato di nuovo. Annotalo o salvalo in un posto sicuro prima di chiudere questa pagina.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ route('report.track', $company->slug) }}"
                    class="btn-brand inline-flex items-center gap-2 py-2.5 px-6 rounded-lg font-semibold text-sm transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Monitora la segnalazione
                </a>
            </div>
        </div>

    @else
        {{-- ── Step 2: Form principale ── --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="accent-bar h-1 w-full"></div>
            <div class="p-8">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Nuova Segnalazione</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Non inserire dati personali se desideri rimanere anonimo. Tutti i contenuti sono cifrati.
                    </p>
                </div>

                <form wire:submit.prevent="submit" class="space-y-6">
                    {{ $this->form }}

                    <div class="pt-2">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="btn-brand w-full py-3 px-4 rounded-lg font-semibold text-sm transition cursor-pointer disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="submit">
                                Invia segnalazione in modo sicuro
                            </span>
                            <span wire:loading wire:target="submit">
                                Invio in corso...
                            </span>
                        </button>
                    </div>
                </form>

                <p class="mt-4 text-xs text-gray-400 text-center">
                    Dopo l'invio riceverai un PIN univoco per monitorare lo stato della tua segnalazione.
                </p>
            </div>
        </div>
    @endif
</div>
