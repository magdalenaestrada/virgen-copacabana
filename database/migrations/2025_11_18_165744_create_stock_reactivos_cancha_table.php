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
        Schema::create('stock_reactivos_cancha', function (Blueprint $table) {
            $table->id();
            $table->datetime("fecha_hora");
            $table->foreignId("usuario_id")->constrained("users");
            $table->char("circuito");
            $table->foreignId("producto_id")->constrained("productos")->index();
            $table->decimal("stock");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reactivos_cancha');
    }
};
