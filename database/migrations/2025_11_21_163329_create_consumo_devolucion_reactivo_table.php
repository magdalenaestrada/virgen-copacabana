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
        Schema::create('consumo_devolucion_reactivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId("proceso_id")->constrained("procesos");
            $table->char("tipo");
            $table->decimal("cantidad");
            $table->foreignId("reactivo_id")->constrained("productos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_devolucion_reactivo');
    }
};
