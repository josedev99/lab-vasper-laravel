<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultHemogramaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_hemograma', function (Blueprint $table) {
            $table->id();
            $table->string('gr_hemato', 20)->nullable();
            $table->string('ht_hemato', 10)->nullable();
            $table->string('hb_hemato', 10)->nullable();
            $table->string('vcm_hemato', 10)->nullable();
            $table->string('cmhc_hemato', 10)->nullable();
            $table->string('gota_hema', 100)->nullable();
            $table->string('gb_hemato', 20)->nullable();
            $table->string('linfocitos_hemato', 10)->nullable();
            $table->string('monocitos_hemato', 10)->nullable();
            $table->string('eosinofilos_hemato', 10)->nullable();
            $table->string('basinofilos_hemato', 10)->nullable();
            $table->string('banda_hemato', 10)->nullable();
            $table->string('segmentados_hemato', 10)->nullable();
            $table->string('metamielo_hemato', 10)->nullable();
            $table->string('mielocitos_hemato', 10)->nullable();
            $table->string('blastos_hemato', 10)->nullable();
            $table->string('plaquetas_hemato', 20)->nullable();
            $table->string('reti_hemato', 10)->nullable();
            $table->string('eritro_hemato', 10)->nullable();
            $table->string('otros_hema', 100)->nullable();
            $table->string('numero_orden', 25)->nullable();
            $table->string('fecha', 25)->nullable();
            $table->string('estado', 10);
            $table->string('hcm_hemato', 25);
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('empresa_id');

            // Relaciones
            $table->foreign('jornada_id')->references('id')->on('jornadas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('empresa_id')->references('id')->on('empresas');

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
        Schema::dropIfExists('result_hemograma');
    }
}
