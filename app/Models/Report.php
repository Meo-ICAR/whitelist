<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'company_id',
        'tracking_token',
        'status',
        'title',
        'description',
    ];

    // Crittografiamo il contenuto per la massima sicurezza legale
    protected function casts(): array
    {
        return [
            'description' => 'encrypted',
        ];
    }

    // Relazione: La segnalazione appartiene a una specifica Azienda
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Relazione: La segnalazione ha molti Messaggi di chat
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
