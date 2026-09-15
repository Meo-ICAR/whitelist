<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;

class AnonymizeExpiredReports extends Command
{
    protected $signature = 'reports:anonymize-expired';

    protected $description = 'Anonimizza le segnalazioni chiuse oltre il periodo di conservazione configurato (data retention)';

    public function handle(): int
    {
        $months = config('whistleblowing.retention_months');

        $reports = Report::query()->dueForAnonymization()->get();

        if ($reports->isEmpty()) {
            $this->info("Nessuna segnalazione da anonimizzare (periodo di conservazione: {$months} mesi).");

            return self::SUCCESS;
        }

        $reports->each->anonymize();

        $this->info("{$reports->count()} segnalazione/i anonimizzata/e (chiuse da più di {$months} mesi).");

        return self::SUCCESS;
    }
}
