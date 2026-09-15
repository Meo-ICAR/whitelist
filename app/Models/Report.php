<?php
namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'company_id',
        'tracking_token',
        'status',
        'title',
        'description',
        'corrective_measures',
    ];

    // Crittografiamo il contenuto per la massima sicurezza legale
    protected function casts(): array
    {
        return [
            'description' => 'encrypted',
            'corrective_measures' => 'encrypted',
            'status' => ReportStatus::class,
            'anonymized_at' => 'datetime',
            'acknowledgement_due_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'feedback_due_at' => 'datetime',
            'acknowledgement_reminder_sent_at' => 'datetime',
            'feedback_reminder_sent_at' => 'datetime',
            'meeting_requested_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Scadenziario D.Lgs. 24/2023: le scadenze decorrono dalla ricezione
        // della segnalazione, non da quando un gestore la esamina.
        static::creating(function (self $report): void {
            $report->acknowledgement_due_at ??= now()->addDays(7);
            $report->feedback_due_at ??= now()->addMonths(3);
        });
    }

    // Audit trail: registra solo i cambi di stato, mai il contenuto in chiaro
    // della segnalazione (che resta cifrato e non deve mai finire nei log).
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
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

    // Media Library: collection 'evidence' su disco privato per gli allegati
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('evidence')
            ->useDisk('private');
    }

    // Scope: filtra le segnalazioni per azienda (tenant isolation)
    public function scopeForCompany(Builder $query, Company $company): Builder
    {
        return $query->where('company_id', $company->id);
    }

    // Scope: segnalazioni chiuse da più tempo del periodo di conservazione
    // configurato, non ancora anonimizzate.
    public function scopeDueForAnonymization(Builder $query): Builder
    {
        $months = config('whistleblowing.retention_months');

        return $query->where('status', ReportStatus::Closed)
            ->whereNull('anonymized_at')
            ->where('updated_at', '<=', now()->subMonths($months));
    }

    // Scope: segnalazioni con avviso di ricevimento scaduto (o in scadenza
    // entro $withinDays giorni) e non ancora confermato.
    public function scopeAcknowledgementDeadlineWithin(Builder $query, int $withinDays = 0): Builder
    {
        return $query->whereNull('acknowledged_at')
            ->whereNotIn('status', [ReportStatus::Closed])
            ->where('acknowledgement_due_at', '<=', now()->addDays($withinDays));
    }

    // Scope: segnalazioni con riscontro finale scaduto (o in scadenza entro
    // $withinDays giorni) e non ancora chiuse.
    public function scopeFeedbackDeadlineWithin(Builder $query, int $withinDays = 0): Builder
    {
        return $query->whereNotIn('status', [ReportStatus::Closed])
            ->where('feedback_due_at', '<=', now()->addDays($withinDays));
    }

    // Segna l'avviso di ricevimento come inviato al segnalante (obbligo di
    // legge entro 7 giorni) e lascia traccia nella chat della pratica.
    public function acknowledge(): void
    {
        $this->forceFill(['acknowledged_at' => now()])->save();

        $this->messages()->create([
            'body' => 'Avviso di ricevimento: la tua segnalazione è stata presa in carico. Riceverai un riscontro entro i termini di legge.',
            'is_from_reporter' => false,
        ]);
    }

    // Il segnalante richiede un incontro diretto col gestore della pratica.
    public function requestMeeting(): void
    {
        $this->forceFill(['meeting_requested_at' => now()])->save();

        $this->messages()->create([
            'body' => 'Il segnalante ha richiesto un incontro diretto per discutere la segnalazione.',
            'is_from_reporter' => true,
        ]);
    }

    // Statistiche completamente anonimizzate per una company (bilancio di
    // sostenibilità ESG - Governance): solo conteggi e medie aggregate,
    // nessun dato identificativo né testo delle segnalazioni.
    public static function anonymizedStatisticsFor(Company $company): array
    {
        $reports = static::query()->where('company_id', $company->id)->get();

        $byStatus = $reports->countBy(fn (self $r) => $r->status->getLabel());
        $closed = $reports->where('status', ReportStatus::Closed);

        $avgDaysToAcknowledge = $reports->whereNotNull('acknowledged_at')
            ->avg(fn (self $r) => $r->created_at->diffInDays($r->acknowledged_at));

        $avgDaysToClose = $closed->avg(fn (self $r) => $r->created_at->diffInDays($r->updated_at));

        return [
            'Periodo esportazione' => now()->format('d/m/Y'),
            'Segnalazioni totali' => $reports->count(),
            'Nuove' => $byStatus->get('Nuova', 0),
            'In lavorazione' => $byStatus->get('In Lavorazione', 0),
            'Chiuse' => $byStatus->get('Chiusa', 0),
            'Avviso di ricevimento inviato entro i termini' => $reports->filter(
                fn (self $r) => $r->acknowledged_at && $r->acknowledged_at->lte($r->acknowledgement_due_at)
            )->count(),
            'Avviso di ricevimento scaduto senza invio' => $reports->filter(
                fn (self $r) => is_null($r->acknowledged_at) && $r->acknowledgement_due_at?->isPast()
            )->count(),
            'Riscontro finale scaduto senza chiusura' => $reports->filter(
                fn (self $r) => $r->status !== ReportStatus::Closed && $r->feedback_due_at?->isPast()
            )->count(),
            'Incontri diretti richiesti' => $reports->whereNotNull('meeting_requested_at')->count(),
            'Giorni medi per avviso di ricevimento' => $avgDaysToAcknowledge ? round($avgDaysToAcknowledge, 1) : '-',
            'Giorni medi per chiusura pratica' => $avgDaysToClose ? round($avgDaysToClose, 1) : '-',
        ];
    }

    // Data retention: rimuove il contenuto (testo, allegati, messaggi) di una
    // segnalazione chiusa oltre il periodo di conservazione, mantenendo solo
    // i metadati necessari per statistiche e audit.
    public function anonymize(): void
    {
        $this->clearMediaCollection('evidence');

        // Aggiornamento riga per riga (non un update() di massa): il campo
        // 'body' è cifrato tramite cast Eloquent, che un update() sul query
        // builder salterebbe, scrivendo il testo in chiaro nel DB.
        foreach ($this->messages as $message) {
            $message->update(['body' => '[Messaggio rimosso per scadenza dei termini di conservazione]']);
        }

        $this->forceFill([
            'title' => '[Segnalazione anonimizzata]',
            'description' => '[Contenuto rimosso per scadenza dei termini di conservazione]',
            'anonymized_at' => now(),
        ])->save();
    }
}
