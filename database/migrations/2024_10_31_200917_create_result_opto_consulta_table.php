<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultOptoConsultaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_opto_consulta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_empleado', 50);
            $table->text('motivo_consulta');
            $table->string('sintomas_consulta', 255)->default('');
            $table->string('fecha_consulta', 25);
            $table->string('hora', 25);
            $table->string('patologias', 250);
            $table->string('diag_optometra', 200)->nullable();
            $table->string('diag_oftalmologo', 200)->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('medicamento')->default('');
            $table->string('av_sc_oi', 255)->default('');
            $table->string('av_sc_od', 255)->default('');
            $table->string('ph_od', 50)->default('');
            $table->string('ph_oi', 50)->default('');
            $table->string('diag_preliminar_od', 200);
            $table->string('diag_preliminar_oi', 200);
            $table->text('observaciones')->nullable();
            $table->string('consulta_externa', 5);
            $table->integer('empleado_id');
            $table->integer('empresa_id');
            $table->integer('sucursal_id');
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
        Schema::dropIfExists('result_opto_consulta');
    }
}
