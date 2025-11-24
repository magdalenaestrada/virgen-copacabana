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
        $table->decimal('suma_descoche', 15, 5)->default(0)->after('suma_comedor');
    });
}

public function down(): void
{
    Schema::table('pl_liquidaciones', function (Blueprint $table) {
        $table->dropColumn('suma_descoche');
    });
}
};
