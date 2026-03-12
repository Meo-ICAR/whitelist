<?php
namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model implements HasAvatar
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'brand_color',
        'shared_passcode',
    ];

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
}
