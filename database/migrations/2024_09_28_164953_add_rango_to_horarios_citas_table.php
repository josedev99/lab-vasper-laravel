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
        Schema::table('horarios_citas', function (Blueprint $table) {
            $table->string('rango', 50)->after('dia'); // Agregar el campo 'rango' después del campo 'dia'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios_citas', function (Blueprint $table) {
            $table->dropColumn('rango'); // Eliminar el campo 'rango' en caso de rollback
        });
    }
};
