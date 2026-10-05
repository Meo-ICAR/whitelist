<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Traccia quando il codice condiviso è stato ruotato l'ultima volta,
            // senza conservare mai i valori precedenti in chiaro.
            $table->timestamp('passcode_rotated_at')->nullable()->after('shared_passcode');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('passcode_rotated_at');
        });
    }
};
