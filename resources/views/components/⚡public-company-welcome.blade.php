<div class="min-h-screen bg-gray-50 flex flex-col items-center py-12 px-4">
    <div class="mb-8">
        @if($company->logo_path)
            <img src="{{ \Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="h-20 object-contain">
        @else
            <h1 class="text-3xl font-bold text-gray-800">{{ $company->name }}</h1>
        @endif
    </div>

    <div class="max-w-4xl w-full bg-white shadow-xl rounded-2xl overflow-hidden">
        <div style="background-color: {{ $company->brand_color }}" class="h-3 w-full"></div>

        <div class="p-8 md:p-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">
                Portale Whistleblowing
            </h2>

            <div class="prose max-w-none text-gray-600 mb-10">
                <p class="text-lg leading-relaxed">
                    Benvenuto nel portale dedicato alle segnalazioni di illeciti di <strong>{{ $company->name }}</strong>.
                    In conformità al D.Lgs. 24/2023, questo spazio ti permette di segnalare violazioni o condotte irregolari garantendo la massima riservatezza e, se desiderato, l'anonimato.
                </p>
                <div class="grid md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <h4 class="text-blue-800 font-bold mb-2">Sicurezza e Privacy</h4>
                        <p class="text-sm text-blue-700">Tutti i dati sono crittografati. La tua identità non verrà rivelata senza il tuo esplicito consenso.</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-xl border border-green-100">
                        <h4 class="text-green-800 font-bold mb-2">Nessuna Ritorsione</h4>
                        <p class="text-sm text-green-700">La legge protegge il segnalante da qualsiasi forma di discriminazione o ritorsione sul lavoro.</p>
                    </div>
                </div>
            </div>

            <hr class="my-10 border-gray-100">

            <div class="grid md:grid-cols-2 gap-8">
                <a href="{{ route('report.form', $company->slug) }}"
                   class="group flex flex-col items-center p-8 border-2 border-gray-100 rounded-2xl hover:border-blue-500 transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">Invia Segnalazione</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Apri una nuova pratica in modo sicuro.</p>
                </a>

                <a href="{{ route('report.tracker', $company->slug) }}"
                   class="group flex flex-col items-center p-8 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 transition-all duration-300">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">Controlla Stato</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Inserisci il tuo PIN per leggere le risposte.</p>
                </a>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-4 text-center">
            <a href="#" class="text-xs text-gray-400 hover:underline">Informativa Privacy Estesa</a>
        </div>
    </div>

    <p class="mt-8 text-gray-400 text-sm italic">Powered by Hassisto Srl</p>
</div>
