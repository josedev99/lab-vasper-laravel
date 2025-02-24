<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultOptoAlturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_opto_altura', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id');
            $table->string('codigo_empleado', 50);
            $table->string('dip_od', 25)->nullable();
            $table->string('dip_oi', 25)->nullable();
            $table->string('ao_od', 25)->nullable();
            $table->string('ao_oi', 25)->nullable();
            $table->string('ap_od', 25)->nullable();
            $table->string('ap_oi', 25)->nullable();
            $table->integer('result_consulta_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_opto_altura');
    }
}
