<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CorrectiveMeasureTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'content',
    ];

    // Relazione richiesta da Filament per lo scoping automatico multi-tenant
    // (ogni azienda vede e gestisce solo i propri modelli).
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
