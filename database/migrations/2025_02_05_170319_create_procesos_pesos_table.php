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
        Schema::create('procesos_pesos', function (Blueprint $table) {
            $table->id();

            $table->double('peso_id');
            $table->unsignedBigInteger('proceso_id');

            // Foreign key constraints
            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procesos_pesos');
    }
};
