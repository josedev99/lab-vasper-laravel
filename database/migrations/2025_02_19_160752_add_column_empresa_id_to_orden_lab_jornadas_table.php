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
        Schema::table('orden_lab_jornadas', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id')->after('jornada_id');
            $table->unsignedBigInteger('empresa_id')->after('empleado_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_lab_jornadas', function (Blueprint $table) {
            $table->dropColumn(['empresa_id', 'empleado_id']);
        });
    }
};
