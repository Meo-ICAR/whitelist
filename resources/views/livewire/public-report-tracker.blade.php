<div>
    @if (!$report)
        {{-- ── Step 1: Inserimento PIN ── --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="accent-bar h-1 w-full"></div>
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Monitora la tua segnalazione</h2>
                        <p class="text-sm text-gray-500">Inserisci il PIN ricevuto al momento dell'invio</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">
                            PIN di tracciamento
                        </label>
                        <input
                            id="pin"
                            type="text"
                            wire:model="pin"
                            wire:keydown.enter="accessReport"
                            placeholder="WHSL-XXXX-XXXX"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition uppercase"
                            autocomplete="off"
                            spellcheck="false"
                        >
                    </div>

                    @if ($errorMessage)
                        <div class="flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $errorMessage }}
                        </div>
                    @endif

                    <button
                        wire:click="accessReport"
                        wire:loading.attr="disabled"
                        class="btn-brand w-full py-2.5 px-4 rounded-lg font-semibold text-sm transition cursor-pointer disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="accessReport">Accedi alla segnalazione</span>
                        <span wire:loading wire:target="accessReport">Ricerca in corso...</span>
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500 mb-3">Non hai ancora inviato una segnalazione?</p>
                    <a href="{{ route('report.welcome', $company->slug) }}"
                       class="text-sm font-medium text-brand hover:underline">
                        Invia una nuova segnalazione →
                    </a>
                </div>
            </div>
        </div>

    @else
        {{-- ── Step 2: Vista segnalazione ── --}}
        <div class="space-y-4">

            {{-- Header segnalazione --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="accent-bar h-1 w-full"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Segnalazione</p>
                            <h2 class="text-xl font-bold text-gray-900 truncate">{{ $report->title }}</h2>
                            <p class="text-xs text-gray-400 mt-1 font-mono">{{ $report->tracking_token }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @php
                                $statusConfig = match($report->status) {
                                    'new'         => ['label' => 'Nuova',        'classes' => 'bg-blue-100 text-blue-700'],
                                    'in_progress' => ['label' => 'In lavorazione','classes' => 'bg-amber-100 text-amber-700'],
                                    'closed'      => ['label' => 'Chiusa',       'classes' => 'bg-gray-100 text-gray-600'],
                                    default       => ['label' => ucfirst($report->status), 'classes' => 'bg-gray-100 text-gray-600'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusConfig['classes'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-500 mb-1">Descrizione</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $report->description }}</p>
                    </div>

                    <p class="text-xs text-gray-400 mt-4">
                        Inviata il {{ $report->created_at->format('d/m/Y \a\l\l\e H:i') }}
                    </p>
                </div>
            </div>

            {{-- Sezione messaggi --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Comunicazioni</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Messaggi tra te e il gestore della segnalazione</p>
                </div>

                <div class="p-6 space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                    @forelse ($report->messages->sortBy('created_at') as $message)
                        <div class="flex {{ $message->is_from_reporter ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-sm">
                                <div class="{{ $message->is_from_reporter
                                    ? 'bg-brand-light border border-brand rounded-2xl rounded-br-sm'
                                    : 'bg-gray-100 rounded-2xl rounded-bl-sm' }} px-4 py-3">
                                    <p class="text-sm text-gray-800 leading-relaxed">{{ $message->body }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 {{ $message->is_from_reporter ? 'text-right' : 'text-left' }}">
                                    {{ $message->is_from_reporter ? 'Tu' : 'Gestore' }}
                                    &middot;
                                    {{ $message->created_at->format('d/m H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm text-gray-400">Nessun messaggio ancora. Il gestore risponderà qui.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Form invio messaggio (solo se non chiusa) --}}
                @if ($report->status !== 'closed')
                    <div class="px-6 pb-6 pt-2 border-t border-gray-100">
                        <div class="flex gap-3">
                            <textarea
                                wire:model="newMessage"
                                placeholder="Scrivi un messaggio al gestore..."
                                rows="2"
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent resize-none transition"
                            ></textarea>
                            <button
                                wire:click="sendMessage"
                                wire:loading.attr="disabled"
                                class="btn-brand flex-shrink-0 self-end px-4 py-2.5 rounded-xl font-semibold text-sm transition cursor-pointer disabled:opacity-60"
                            >
                                <span wire:loading.remove wire:target="sendMessage">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="sendMessage">...</span>
                            </button>
                        </div>
                        @error('newMessage')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div class="px-6 pb-6 pt-2 border-t border-gray-100">
                        <p class="text-sm text-gray-400 text-center py-2">
                            Questa segnalazione è stata chiusa. Non è più possibile inviare messaggi.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Torna alla ricerca --}}
            <div class="text-center">
                <button
                    wire:click="$set('report', null)"
                    class="text-sm text-gray-400 hover:text-gray-600 hover:underline transition"
                >
                    ← Cerca un'altra segnalazione
                </button>
            </div>
        </div>
    @endif
</div>
