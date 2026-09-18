<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($company) ? $company->name . ' — Whistleblowing' : config('app.name') }}</title>

    {{-- Foglio di stile statico (nessuna build: niente Vite/Node in produzione) --}}
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">

    {{-- Stili dei componenti Filament Forms usati da {{ $this->form }} (TextInput,
         Textarea, upload allegati): assets pubblicati dal pacchetto, non richiedono
         una build applicativa. @filamentStyles include solo variabili colore/font;
         il tema compilato con le classi fi-* va linkato a parte, perché normalmente
         lo include il layout del pannello admin, che qui non c'è. --}}
    @filamentStyles
    <link rel="stylesheet" href="{{ asset('css/filament/filament/app.css') }}">

    @php
        $brandColor = (isset($company) && $company->brand_color) ? $company->brand_color : '#1d4ed8';
    @endphp
    <style>
        :root {
            --brand-color: {{ $brandColor }};
            --brand-color-light: {{ $brandColor }}22;
            /* Il form pubblico non passa dal pannello admin, quindi le classi
               fi-* di Filament non ricevono il brand_color dinamico via
               ->colors() dell'AdminPanelProvider: riapplichiamo qui lo stesso
               colore alle variabili "primary" usate dai componenti dei form. */
            --primary-50: color-mix(in srgb, {{ $brandColor }} 5%, white);
            --primary-100: color-mix(in srgb, {{ $brandColor }} 10%, white);
            --primary-200: color-mix(in srgb, {{ $brandColor }} 25%, white);
            --primary-300: color-mix(in srgb, {{ $brandColor }} 45%, white);
            --primary-400: color-mix(in srgb, {{ $brandColor }} 70%, white);
            --primary-500: {{ $brandColor }};
            --primary-600: color-mix(in srgb, {{ $brandColor }} 85%, black);
            --primary-700: color-mix(in srgb, {{ $brandColor }} 70%, black);
            --primary-800: color-mix(in srgb, {{ $brandColor }} 55%, black);
            --primary-900: color-mix(in srgb, {{ $brandColor }} 40%, black);
            --primary-950: color-mix(in srgb, {{ $brandColor }} 25%, black);
        }
        .btn-brand { background-color: var(--brand-color); color: #fff; }
        .btn-brand:hover { opacity: 0.9; }
        .border-brand { border-color: var(--brand-color); }
        .text-brand { color: var(--brand-color); }
        .bg-brand-light { background-color: var(--brand-color-light); }
        .accent-bar { background-color: var(--brand-color); }
    </style>

    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Top accent bar --}}
    <div class="accent-bar h-1 w-full"></div>

    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-4">
            @if(isset($company))
                @if($company->logo_path)
                    <img src="{{ $company->getFilamentAvatarUrl() }}"
                         alt="{{ $company->name }}"
                         class="h-10 object-contain">
                @endif
                @if($company->shouldShowNameInPublicHeader())
                    <span class="text-xl font-bold text-gray-800">{{ $company->name }}</span>
                @endif
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">Portale Whistleblowing</span>
                <a href="{{ route('report.guide', $company->slug) }}" class="ml-auto text-sm font-medium text-brand hover:underline">
                    Come funziona?
                </a>
            @else
                <span class="text-xl font-bold text-gray-800">Whistleblowing</span>
            @endif
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-1 flex flex-col items-center justify-start py-10 px-4">
        <div class="w-full max-w-2xl">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-4 text-center text-xs text-gray-400">
        Piattaforma conforme al D.Lgs. 24/2023 &mdash; Tutti i dati sono cifrati
    </footer>

    {{-- Registratore vocale con alteratore del timbro: file statico, nessuna
         build. Registra il listener 'alpine:init' prima che Livewire avvii
         Alpine.js negli script sotto. --}}
    <script src="{{ asset('js/voice-recorder.js') }}"></script>

    @livewireScripts
    @filamentScripts
</body>
</html>
