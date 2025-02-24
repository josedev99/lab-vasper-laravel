<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFieldsInColesterolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('result_colesterol', function (Blueprint $table) {
            $table->renameColumn('id_jornada', 'jornada_id');
            $table->renameColumn('id_empleado', 'empleado_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('result_colesterol', function (Blueprint $table) {
            $table->renameColumn('jornada_id', 'id_jornada');
            $table->renameColumn('empleado_id', 'id_empleado');
        });
    }
}
