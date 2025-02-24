<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultOrinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_orina', function (Blueprint $table) {
            $table->id();
            $table->string('color', 15)->nullable();
            $table->string('olor', 15)->nullable();
            $table->string('aspecto', 25)->nullable();
            $table->string('densidad', 10)->nullable();
            $table->string('est_leuco', 15)->nullable();
            $table->string('ph', 6)->nullable();
            $table->string('proteinas', 20)->nullable();
            $table->string('glucosa', 20)->nullable();
            $table->string('cetonas', 20)->nullable();
            $table->string('urobilinogeno', 20)->nullable();
            $table->string('bilirrubina', 20)->nullable();
            $table->string('sangre_oculta', 20)->nullable();
            $table->string('cilindros', 100)->nullable();
            $table->string('leucocitos', 25)->nullable();
            $table->string('hematies', 25)->nullable();
            $table->string('cel_epiteliales', 50)->nullable();
            $table->string('filamentos_muco', 50)->nullable();
            $table->string('bacterias', 25)->nullable();
            $table->string('cristales', 200)->nullable();
            $table->string('observaciones', 150)->nullable();
            $table->string('nitritos_orina', 50)->nullable();
            $table->string('estado')->nullable();
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('empresa_id');
            
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
        Schema::dropIfExists('result_orina');
    }
}
