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
        Schema::create('reactivos_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reactivo_id');
            $table->decimal('precio', 7, 5);
            $table->decimal('limite', 7, 5);

            // Foreign key constraints
            $table->foreign('reactivo_id')->references('id')->on('reactivos')->onDelete('restrict');
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactivos_detalles');
    }
};
