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
        Schema::create('consumo_reactivos', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad', 8, 5);

            $table->unsignedBigInteger('proceso_id');
            $table->unsignedBigInteger('reactivo_id');

            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('restrict');
            $table->foreign('reactivo_id')->references('id')->on('reactivos')->onDelete('restrict');
            $table->timestamp('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_reactivos');
    }
};
