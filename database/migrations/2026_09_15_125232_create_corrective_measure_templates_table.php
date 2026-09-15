<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('corrective_measure_templates', function (Blueprint $table) {
            $table->id();
            // Nullable: se null il modello è "globale", richiamabile da
            // qualunque azienda ma non modificabile/eliminabile da nessuna
            // (gestito solo da seeder/console). Se valorizzato, il modello è
            // di proprietà esclusiva di quell'azienda.
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_measure_templates');
    }
};
