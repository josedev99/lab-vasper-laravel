<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetJerarquiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_jerarquia', function (Blueprint $table) {
            $table->id();
            $table->Integer('tipo');
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            // Opcional: Si tienes una relación con la tabla `empresas`, puedes agregar la clave foránea:
             $table->foreign('id_empresa')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_jerarquia');
    }
}
