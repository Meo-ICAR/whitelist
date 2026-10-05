<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Quando l'azienda ha un logo, di default nell'header delle
            // pagine pubbliche di segnalazione viene mostrato solo il logo
            // (comportamento esistente). Questo flag permette di mostrare
            // anche il nome dell'azienda accanto al logo. Senza logo, il
            // nome viene sempre mostrato indipendentemente da questo flag.
            $table->boolean('show_name_with_logo')->default(false)->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('show_name_with_logo');
        });
    }
};
