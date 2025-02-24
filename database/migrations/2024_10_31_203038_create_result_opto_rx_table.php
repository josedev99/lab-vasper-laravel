<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultOptoRxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_opto_rx', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id');
            $table->string('codigo_empleado', 50);
            $table->string('od_esfera_f', 25)->nullable();
            $table->string('od_cilindro_f', 25)->nullable();
            $table->string('od_eje_f', 25)->nullable();
            $table->string('od_prisma_f', 25)->nullable();
            $table->string('od_adicion_f', 25)->nullable();
            $table->string('oi_esfera_f', 25)->nullable();
            $table->string('oi_cilindro_f', 25)->nullable();
            $table->string('oi_eje_f', 25)->nullable();
            $table->string('oi_prisma_f', 25)->nullable();
            $table->string('oi_adicion_f', 25)->nullable();
            $table->string('oi_av_final_cc', 255)->default('');
            $table->string('od_av_final_cc', 255)->default('');
            $table->integer('result_consulta_id');
            $table->integer('jornada_id');
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
        Schema::dropIfExists('result_opto_rx');
    }
}
