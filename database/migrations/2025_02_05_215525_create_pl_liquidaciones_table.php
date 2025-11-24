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
        Schema::create('pl_liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->decimal('suma_proceso', 15, 5);
            $table->decimal('suma_exceso_reactivos', 15, 5);
            $table->decimal('suma_balanza', 15, 5);
            $table->decimal('suma_laboratorio', 15, 5);
            $table->decimal('suma_prueba_metalurgica', 15, 5);
            $table->decimal('suma_comedor', 15, 5);
            $table->decimal('gastos_adicionales', 15, 5);
            $table->decimal('subtotal', 15, 5);
            $table->decimal('igv', 15, 5);
            $table->decimal('total', 15, 5);
            $table->date('fecha');
            $table->unsignedBigInteger('proceso_id');
            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('restrict');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('lq_clientes')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_liquidaciones');
    }
};
