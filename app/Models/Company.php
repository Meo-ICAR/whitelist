<?php

namespace App\Models;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Company extends Model implements HasAvatar
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'brand_color',
        'shared_passcode',
        'passcode_rotated_at',
        'webmaster_email',
    ];

    protected function casts(): array
    {
        return [
            'passcode_rotated_at' => 'datetime',
        ];
    }

    // Ogni volta che il codice condiviso cambia, registriamo SOLO il momento
    // della rotazione: il valore del codice non deve mai comparire nei log
    // applicativi o nell'audit trail.
    protected function sharedPasscode(): Attribute
    {
        return Attribute::make(
            // Un mutatore "set" può restituire un array per aggiornare più
            // attributi in una volta sola: qui aggiorniamo anche
            // passcode_rotated_at quando il codice cambia davvero.
            set: function (?string $value) {
                $hasChanged = ($this->attributes['shared_passcode'] ?? null) !== $value;

                return [
                    'shared_passcode' => $value,
                    'passcode_rotated_at' => $hasChanged ? now() : ($this->attributes['passcode_rotated_at'] ?? null),
                ];
            },
        );
    }

    // Audit trail: mai il valore del passcode, solo i campi non sensibili
    // e la data dell'ultima rotazione del codice.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'brand_color', 'passcode_rotated_at', 'webmaster_email'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    // Relazione: Un'azienda ha molti Gestori (Utenti del pannello)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // Relazione: Un'azienda riceve molte Segnalazioni
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Questo metodo dice a Filament quale nome mostrare nel selettore
    public function getTenantModelLabel(): string
    {
        return 'Azienda Cliente';
    }

    // Questo metodo permette di mostrare il LOGO dell'azienda nel selettore
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo_path
            ? Storage::url($this->logo_path)
            : null;
    }

    // Immagine PNG del QR code che punta al link pubblico di segnalazione di
    // questa azienda. Usata sia dalla rotta di download pubblico (route
    // 'report.qrcode') sia dall'email inviata al webmaster, per non
    // duplicare la configurazione del generatore in due posti.
    public function qrCodePng(): string
    {
        return Builder::create()
            ->writer(new PngWriter)
            ->data(route('report.welcome', ['company' => $this->slug]))
            ->size(600)
            ->margin(16)
            ->build()
            ->getString();
    }
}
