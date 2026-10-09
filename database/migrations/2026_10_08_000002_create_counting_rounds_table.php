<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ronda de conteo de una toma: C1/C2 (doble conteo a ciegas) o C3 (árbitro).
     * location_id referencia product_locations del core (solo lectura, sin tocarlo).
     */
    public function up(): void
    {
        Schema::create('counting_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counting_id')->constrained('countings')->cascadeOnDelete();
            $table->foreignId('product_location_id')->constrained('product_locations')->cascadeOnDelete();
            $table->string('round_type'); // c1 | c2 | c3
            $table->string('status')->default('pending'); // pending | open | closed
            $table->timestamps();

            $table->unique(['counting_id', 'product_location_id', 'round_type']);
            $table->index(['counting_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counting_rounds');
    }
};
