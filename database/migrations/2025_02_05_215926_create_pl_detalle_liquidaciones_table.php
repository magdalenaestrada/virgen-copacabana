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
        Schema::create('pl_detalle_liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio_unitario_proceso', 15, 5);
            $table->decimal('cantidad_procesada_tn', 15, 5);
            $table->decimal('precio_unitario_laboratorio', 15, 5);
            $table->integer('cantidad_muestras');
            $table->decimal('precio_unitario_balanza', 15, 5);
            $table->integer('cantidad_pesajes');
            $table->decimal('precio_prueba_metalurgica', 15, 5);
            $table->integer('cantidad_pruebas_metalurgicas');
            $table->decimal('precio_unitario_comida', 15, 5);
            $table->integer('cantidad_comidas');
            $table->unsignedBigInteger('liquidacion_id');
            $table->foreign('liquidacion_id')->references('id')->on('pl_liquidaciones')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_detalle_liquidaciones');
    }
};
