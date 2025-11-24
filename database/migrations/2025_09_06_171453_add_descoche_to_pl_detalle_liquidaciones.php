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
    Schema::table('pl_detalle_liquidaciones', function (Blueprint $table) {
        $table->decimal('precio_descoche', 15, 5)->default(0);
        $table->integer('cantidad_descoche')->default(0);
    });
}

public function down(): void
{
    Schema::table('pl_detalle_liquidaciones', function (Blueprint $table) {
        $table->dropColumn(['precio_descoche', 'cantidad_descoche']);
    });
}
};
