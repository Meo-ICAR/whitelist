<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

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
}
