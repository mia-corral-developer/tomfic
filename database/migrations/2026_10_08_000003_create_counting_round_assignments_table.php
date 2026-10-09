<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Capturador asignado a una ronda. Un capturador solo ve las rondas a él asignadas.
     */
    public function up(): void
    {
        Schema::create('counting_round_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counting_round_id')->constrained('counting_rounds')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['counting_round_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counting_round_assignments');
    }
};
