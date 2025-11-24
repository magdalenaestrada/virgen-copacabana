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
        Schema::table('pl_liquidaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('creador_id')->nullable();
            $table->foreign('creador_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pl_liquidaciones', function (Blueprint $table) {
            $table->dropForeign(['creador_id']); // Drop the foreign key
            $table->dropColumn('creador_id');    // Drop the column
        });
    }
};
