<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJornadaIdToResultOptoAlturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('result_opto_altura', function (Blueprint $table) {
            $table->integer('jornada_id')->after('result_consulta_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('result_opto_altura', function (Blueprint $table) {
            $table->dropColumn('jornada_id');
        });
    }
}
