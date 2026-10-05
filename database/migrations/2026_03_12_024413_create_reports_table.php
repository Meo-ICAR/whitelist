<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_token')->unique();  // Il PIN rilasciato al segnalante
            $table->string('status')->default('new');  // new, in_progress, closed
            $table->string('title');
            $table->text('description');  // Assicurati di usare il cast 'encrypted' nel Modello
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
