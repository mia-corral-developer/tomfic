<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Captura de un producto en una ronda.
     * unique(round, product): el re-escaneo del mismo producto REEMPLAZA la cantidad,
     * nunca duplica. El C1 y C2 del mismo producto son filas en rondas distintas.
     */
    public function up(): void
    {
        Schema::create('counting_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counting_round_id')->constrained('counting_rounds')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('units')->default(0);
            $table->unsignedInteger('boxes')->default(0);
            $table->string('photo_path')->nullable(); // evidencia fotográfica
            $table->foreignId('counted_by')->constrained('users');
            $table->timestamps();

            $table->unique(['counting_round_id', 'product_id']);
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counting_items');
    }
};
