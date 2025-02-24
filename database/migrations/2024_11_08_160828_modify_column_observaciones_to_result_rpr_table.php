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
        Schema::table('result_rpr', function (Blueprint $table) {
            $table->renameColumn('id_jornada','jornada_id');
            $table->renameColumn('id_empleado','empleado_id');
            $table->renameColumn('observacione','observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_rpr', function (Blueprint $table) {
            $table->renameColumn('jornada_id','id_jornada');
            $table->renameColumn('empleado_id','id_empleado');
            $table->renameColumn('observaciones','observacione');
        });
    }
};
