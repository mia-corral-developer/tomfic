<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unidades por caja del producto (configurable): cálculo de totales de captura.
     * Fórmula acordada con el dueño: total = (cajas × units_per_box) + unidades sueltas.
     * Default 1 = caja independiente (compatibilidad con productos cero sin caja o sin config).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('units_per_box')->default(1)->after('barcode');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('units_per_box');
        });
    }
};
