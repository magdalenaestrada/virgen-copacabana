<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesos_otras_bal', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fechai')->nullable();
            $table->timestamp('fechas')->nullable();
            $table->bigInteger('bruto')->nullable();
            $table->bigInteger('tara')->nullable();
            $table->unsignedBigInteger('neto')->default(0);
            $table->string('placa')->nullable();
            $table->string('observacion')->nullable();
            $table->string('producto')->nullable();
            $table->string('conductor')->nullable();
            $table->foreignId("razon_social_id")->constrained("lq_clientes");
            $table->foreignId("programacion_id")->constrained("pl_programaciones");
            $table->string('guia')->nullable();
            $table->string('guiat')->nullable();
            $table->string('origen')->nullable();
            $table->string('destino')->nullable();
            $table->string('balanza')->nullable();

            $table->unsignedBigInteger('lote_id');
            $table->foreign('lote_id')->references(columns: 'id')->on('lotes')->onDelete('restrict');
            $table->unsignedBigInteger('proceso_id');
            $table->foreign('proceso_id')->references(columns: 'id')->on('procesos')->onDelete('restrict');
            $table->unsignedBigInteger('estado_id');
            $table->foreign('estado_id')->references(columns: 'id')->on('ps_estados')->onDelete('restrict');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesos_otras_bal');
    }
};
