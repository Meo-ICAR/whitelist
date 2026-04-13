<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($company) ? $company->name . ' — Whistleblowing' : config('app.name') }}</title>

    {{-- Filament's compiled CSS includes Tailwind --}}
    <link rel="stylesheet" href="{{ asset('css/filament/filament/app.css') }}">

    @if(isset($company) && $company->brand_color)
    <style>
        :root {
            --brand-color: {{ $company->brand_color }};
            --brand-color-light: {{ $company->brand_color }}22;
        }
        .btn-brand {
            background-color: var(--brand-color);
            color: #fff;
        }
        .btn-brand:hover {
            opacity: 0.9;
        }
        .border-brand {
            border-color: var(--brand-color);
        }
        .text-brand {
            color: var(--brand-color);
        }
        .bg-brand-light {
            background-color: var(--brand-color-light);
        }
        .accent-bar {
            background-color: var(--brand-color);
        }
    </style>
    @else
    <style>
        :root { --brand-color: #1d4ed8; }
        .btn-brand { background-color: #1d4ed8; color: #fff; }
        .btn-brand:hover { opacity: 0.9; }
        .border-brand { border-color: #1d4ed8; }
        .text-brand { color: #1d4ed8; }
        .bg-brand-light { background-color: #1d4ed822; }
        .accent-bar { background-color: #1d4ed8; }
    </style>
    @endif

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
                    <img src="{{ \Storage::url($company->logo_path) }}"
                         alt="{{ $company->name }}"
                         class="h-10 object-contain">
                @else
                    <span class="text-xl font-bold text-gray-800">{{ $company->name }}</span>
                @endif
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">Portale Whistleblowing</span>
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

    @livewireScripts
</body>
</html>
