<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Scadenziario D.Lgs. 24/2023: avviso di ricevimento entro 7
            // giorni, riscontro finale entro 3 mesi dalla segnalazione.
            $table->timestamp('acknowledgement_due_at')->nullable()->after('status');
            $table->timestamp('acknowledged_at')->nullable()->after('acknowledgement_due_at');
            $table->timestamp('feedback_due_at')->nullable()->after('acknowledged_at');
            $table->timestamp('acknowledgement_reminder_sent_at')->nullable()->after('feedback_due_at');
            $table->timestamp('feedback_reminder_sent_at')->nullable()->after('acknowledgement_reminder_sent_at');

            // Richiesta di incontro diretto col gestore.
            $table->timestamp('meeting_requested_at')->nullable()->after('feedback_reminder_sent_at');

            // Registro delle misure correttive intraprese (testo cifrato).
            $table->text('corrective_measures')->nullable()->after('meeting_requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'acknowledgement_due_at',
                'acknowledged_at',
                'feedback_due_at',
                'acknowledgement_reminder_sent_at',
                'feedback_reminder_sent_at',
                'meeting_requested_at',
                'corrective_measures',
            ]);
        });
    }
};
