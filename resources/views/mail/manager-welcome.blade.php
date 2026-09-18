<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Benvenuto su {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; color:#1f2933;">
    <div style="max-width:600px; margin:0 auto; padding:24px 16px;">

        <div style="background:#1d4ed8; height:4px; border-radius:4px; margin-bottom:24px;"></div>

        <h1 style="font-size:20px; margin:0 0 8px;">Benvenuto su {{ config('app.name') }}</h1>

        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; margin-bottom:20px;">
            <p style="color:#1e3a5f; font-size:13px; margin:0;">
                Il canale di segnalazione whistleblowing per
                <strong>{{ $companies->pluck('name')->implode(', ') }}</strong>
                è attivo. Qui sotto trovi le credenziali per accedere al pannello di gestione delle
                segnalazioni e i manuali che ti guidano nell'uso della piattaforma.
            </p>
        </div>

        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 12px;">Le tue credenziali di accesso</h2>
            <table style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:12px;">
                <tr>
                    <td style="padding:6px 0; color:#52606d; width:35%;">Indirizzo pannello</td>
                    <td style="padding:6px 0;"><a href="{{ route('filament.admin.auth.login') }}" style="color:#1d4ed8;">{{ route('filament.admin.auth.login') }}</a></td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Email</td>
                    <td style="padding:6px 0; font-family:monospace;">{{ $manager->email }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#52606d; vertical-align:top;">Password provvisoria</td>
                    <td style="padding:6px 0;">
                        <span style="font-family:monospace; font-size:14px; background:#f8fafc; border:1px solid #d9e2ec; border-radius:6px; padding:4px 10px; display:inline-block; letter-spacing:.03em;">{{ $plainPassword }}</span>
                    </td>
                </tr>
            </table>
            <p style="font-size:12px; color:#9aa5b1; margin:0;">
                Per motivi di sicurezza, ti consigliamo di cambiarla al primo accesso: dopo il login vai
                sulla Dashboard e usa il riquadro "Account" in alto per impostare una nuova password.
            </p>
        </div>

        <div style="background:#fff; border:1px solid #d9e2ec; border-radius:12px; padding:20px; margin-bottom:16px;">
            <h2 style="font-size:15px; margin:0 0 12px;">Manuali</h2>
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Manuale utente (per te, gestore)</td>
                    <td style="padding:6px 0; text-align:right;"><a href="{{ route('docs.manuale-utente') }}" style="color:#1d4ed8;">Scarica il PDF</a></td>
                </tr>
                @foreach($companies as $company)
                <tr>
                    <td style="padding:6px 0; color:#52606d;">Guida per chi segnala — {{ $company->name }}</td>
                    <td style="padding:6px 0; text-align:right;"><a href="{{ route('report.guide', ['company' => $company->slug]) }}" style="color:#1d4ed8;">Apri la guida</a></td>
                </tr>
                @endforeach
            </table>
        </div>

        <p style="font-size:12px; color:#9aa5b1; text-align:center; margin-top:24px;">
            Email generata automaticamente da {{ config('app.name') }} alla creazione del tuo account gestore.
        </p>
    </div>
</body>
</html>
