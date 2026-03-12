<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10">

    @if(!$report)
        <h2 class="text-2xl font-bold mb-4">Controlla la tua segnalazione</h2>
        <p class="text-gray-600 mb-4">Inserisci il PIN che ti è stato fornito al momento dell'invio per accedere alla bacheca sicura.</p>

        <form wire:submit="accessReport" class="flex gap-4">
            <input type="text" wire:model="pin" placeholder="Es: WHSL-A8F2-9K1M" class="border p-2 flex-grow rounded uppercase tracking-widest">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Accedi</button>
        </form>

        @if($errorMessage)
            <p class="text-red-500 mt-2">{{ $errorMessage }}</p>
        @endif

    @else
        <div class="border-b pb-4 mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold">Pratica: {{ $report->tracking_token }}</h2>
            <span class="px-3 py-1 bg-gray-200 rounded-full text-sm font-semibold">
                Stato: {{ strtoupper($report->status) }}
            </span>
        </div>

        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded">
            @forelse($report->messages as $message)
                <div class="flex {{ $message->is_from_reporter ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-lg p-3 rounded-lg {{ $message->is_from_reporter ? 'bg-blue-600 text-white' : 'bg-white border text-gray-800' }}">
                        <p class="text-sm font-bold mb-1">
                            {{ $message->is_from_reporter ? 'Tu' : 'Gestore' }}
                        </p>
                        <p>{{ $message->body }}</p>
                        <span class="text-xs opacity-75 mt-2 block">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Nessun messaggio presente.</p>
            @endforelse
        </div>

        <form wire:submit="sendMessage">
            <textarea wire:model="newMessage" rows="3" class="w-full border p-3 rounded mb-2" placeholder="Scrivi un messaggio sicuro al gestore..."></textarea>
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Invia Messaggio
                </button>
            </div>
        </form>
    @endif
</div>
