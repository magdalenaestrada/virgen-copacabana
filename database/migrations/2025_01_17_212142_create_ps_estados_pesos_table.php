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
        Schema::create('ps_estados_pesos', function (Blueprint $table) {
            $table->id();
            $table->double('peso_id')->unique();
            $table->unsignedBigInteger('estado_id');

            // Foreign key constraints
            $table->foreign('estado_id')->references('id')->on('ps_estados')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ps_estados_pesos');
    }
};
