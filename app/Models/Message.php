<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Message extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'report_id',
        'body',
        'is_from_reporter',
    ];

    protected function casts(): array
    {
        return [
            'body' => 'encrypted',  // I messaggi sono salvati illeggibili nel DB
            'is_from_reporter' => 'boolean',
        ];
    }

    // Relazione: Il messaggio appartiene a una specifica Segnalazione
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    // Audit trail: registriamo solo il fatto e il mittente (segnalante/gestore),
    // mai il testo del messaggio, che resta cifrato e riservato.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_from_reporter'])
            ->dontLogEmptyChanges();
    }
}
