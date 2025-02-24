<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJornadaIdToResultOptoConsultaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('result_opto_consulta', function (Blueprint $table) {
            $table->integer('jornada_id')->after('sucursal_id'); // Agregar el nuevo campo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('result_opto_consulta', function (Blueprint $table) {
            $table->dropColumn('jornada_id'); // Eliminar el campo si se revierte la migración
        });
    }
}
