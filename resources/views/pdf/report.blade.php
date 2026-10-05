<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Segnalazione {{ $report->tracking_token }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2933; }
        h1 { font-size: 18px; margin-bottom: 0; }
        .meta { color: #52606d; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 14px; border-bottom: 1px solid #d9e2ec; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        table td, table th { padding: 6px 4px; border-bottom: 1px solid #eee; text-align: left; vertical-align: top; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 8px; font-size: 11px; }
        .footer { margin-top: 30px; font-size: 10px; color: #9aa5b1; }
    </style>
</head>
<body>
    <h1>Fascicolo Segnalazione</h1>
    <p class="meta">
        Azienda: {{ $report->company->name }} &middot;
        PIN: {{ $report->tracking_token }} &middot;
        Esportato il {{ now()->format('d/m/Y H:i') }}
    </p>

    <div class="section">
        <h2>Dettagli</h2>
        <table>
            <tr><th>Oggetto</th><td>{{ $report->title }}</td></tr>
            <tr><th>Stato</th><td>{{ $report->status->getLabel() }}</td></tr>
            <tr><th>Ricevuta il</th><td>{{ $report->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Descrizione dei fatti</h2>
        <p>{{ $report->description }}</p>
    </div>

    <div class="section">
        <h2>Comunicazioni</h2>
        <table>
            <thead>
                <tr><th>Data</th><th>Mittente</th><th>Messaggio</th></tr>
            </thead>
            <tbody>
                @forelse ($report->messages->sortBy('created_at') as $message)
                    <tr>
                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $message->is_from_reporter ? 'Segnalante' : 'Gestore' }}</td>
                        <td>{{ $message->body }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">Nessun messaggio.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="footer">
        Documento riservato generato automaticamente. Non condividere al di fuori del team autorizzato alla gestione delle segnalazioni.
    </p>
</body>
</html>
