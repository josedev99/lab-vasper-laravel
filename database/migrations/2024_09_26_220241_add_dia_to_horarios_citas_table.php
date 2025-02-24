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
            $table->string('dia', 20)->after('hora'); // Agregar el campo 'dia' después del campo 'hora'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios_citas', function (Blueprint $table) {
            $table->dropColumn('dia'); // Eliminar el campo 'dia' en caso de rollback
        });
    }
};
