<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Canale di segnalazione {{ $company->name }}</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; color:#1f2933;">
    <div style="max-width:600px; margin:0 auto; padding:24px 16px;">

        <div style="background:{{ $company->brand_color ?: '#1d4ed8' }}; height:4px; border-radius:4px; margin-bottom:24px;"></div>

        <h1 style="font-size:20px; margin:0 0 8px;">Canale di segnalazione — {{ $company->name }}</h1>

        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; margin-bottom:20px;">
            <p style="color:#1e3a5f; font-size:13px; margin:0;">
                <strong>Perché ricevi questa email:</strong> {{ $company->name }} ha attivato su
                {{ config('app.name') }} il proprio canale di segnalazione whistleblowing, conforme al
                D.Lgs. 24/2023, e ti ha indicato come referente tecnico (webmaster) del sito aziendale.
                Qui sotto trovi link, codice di accesso, QR code e snippet HTML pronti da pubblicare sul
                sito. Per le istruzioni complete su dove e come integrarli, consulta il
                <a href="{{ route('docs.manuale-webmaster') }}" style="color:#1d4ed8;">manuale per il webmaster</a>.
            </p>
        </div>

        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 12px;">Link pubblici</h2>
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Invia una segnalazione</td>
                    <td style="padding:6px 0; text-align:right;"><a href="{{ route('report.welcome', ['company' => $company->slug]) }}" style="color:#1d4ed8;">{{ route('report.welcome', ['company' => $company->slug]) }}</a></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Traccia una segnalazione</td>
                    <td style="padding:6px 0; text-align:right;"><a href="{{ route('report.track', ['company' => $company->slug]) }}" style="color:#1d4ed8;">{{ route('report.track', ['company' => $company->slug]) }}</a></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Guida per chi segnala</td>
                    <td style="padding:6px 0; text-align:right;"><a href="{{ route('report.guide', ['company' => $company->slug]) }}" style="color:#1d4ed8;">{{ route('report.guide', ['company' => $company->slug]) }}</a></td>
                </tr>
            </table>
            <p style="font-size:12px; color:#9aa5b1; margin:12px 0 0;">
                Consigliato come link principale sul sito: la pagina "Guida per chi segnala" spiega sia l'invio sia il tracciamento.
            </p>
        </div>

        @if($company->shared_passcode)
        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 8px;">Codice di accesso</h2>
            <p style="font-size:13px; color:#52606d; margin:0 0 8px;">
                Questo canale richiede un codice condiviso prima di poter compilare il modulo di segnalazione.
                Comunicalo tu stesso ai visitatori del sito (es. nella stessa pagina, o via canali interni), non è
                previsto invio automatico ai segnalanti.
            </p>
            <p style="font-family:monospace; font-size:16px; background:#f8fafc; border:1px solid #d9e2ec; border-radius:6px; padding:8px 12px; display:inline-block; letter-spacing:.05em;">
                {{ $company->shared_passcode }}
            </p>
        </div>
        @endif

        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 8px;">QR code</h2>
            <p style="font-size:13px; color:#52606d; margin:0 0 8px;">
                In allegato a questa email trovi il QR code in formato PNG (600×600px, adatto anche alla stampa),
                pronto da caricare nella libreria media del tuo sito/CMS.
            </p>
            <p style="font-size:13px; color:#52606d; margin:0;">
                In alternativa puoi collegarlo direttamente, senza scaricarlo, con questo link permanente:<br>
                <a href="{{ route('report.qrcode', ['company' => $company->slug]) }}" style="color:#1d4ed8;">{{ route('report.qrcode', ['company' => $company->slug]) }}</a>
            </p>
        </div>

        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 12px;">Codice HTML pronto per {{ $company->name }}</h2>

            <p style="font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#9aa5b1; margin:0 0 4px;">Bottone singolo (link alla guida)</p>
            <pre style="background:#0f172a; color:#e2e8f0; padding:12px; border-radius:8px; overflow-x:auto; font-size:12px; line-height:1.5; margin:0 0 16px;"><code>&lt;a href="{{ route('report.guide', ['company' => $company->slug]) }}"
   target="_blank" rel="noopener"&gt;
  Segnala un illecito
&lt;/a&gt;</code></pre>

            <p style="font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#9aa5b1; margin:0 0 4px;">Due bottoni separati (invio + tracciamento)</p>
            <pre style="background:#0f172a; color:#e2e8f0; padding:12px; border-radius:8px; overflow-x:auto; font-size:12px; line-height:1.5; margin:0 0 16px;"><code>&lt;div class="whistleblowing-links"&gt;
  &lt;a href="{{ route('report.welcome', ['company' => $company->slug]) }}" target="_blank" rel="noopener"&gt;
    Invia una segnalazione
  &lt;/a&gt;
  &lt;a href="{{ route('report.track', ['company' => $company->slug]) }}" target="_blank" rel="noopener"&gt;
    Traccia la tua segnalazione
  &lt;/a&gt;
&lt;/div&gt;</code></pre>

            <p style="font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#9aa5b1; margin:0 0 4px;">Immagine QR code incorporata</p>
            <pre style="background:#0f172a; color:#e2e8f0; padding:12px; border-radius:8px; overflow-x:auto; font-size:12px; line-height:1.5; margin:0;"><code>&lt;img src="{{ route('report.qrcode', ['company' => $company->slug]) }}"
     alt="QR code segnalazioni {{ $company->name }}" width="200" height="200"&gt;</code></pre>
        </div>

        <p style="font-size:12px; color:#9aa5b1; text-align:center; margin-top:24px;">
            Email generata automaticamente da {{ config('app.name') }} su richiesta del gestore del canale di segnalazione di {{ $company->name }}.
        </p>
    </div>
</body>
</html>
