<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Operatore SaaS: gestisce aziende clienti e gestori da un
            // pannello unico, senza mai accedere al contenuto delle
            // segnalazioni di nessuna azienda (vedi ReportPolicy).
            $table->boolean('is_superadmin')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_superadmin');
        });
    }
};
