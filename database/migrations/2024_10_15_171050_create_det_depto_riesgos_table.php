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
        Schema::create('det_depto_riesgos', function (Blueprint $table) {
            $table->id();
            $table->string('fecha',15);
            $table->string('hora',15);
            $table->unsignedBigInteger('area_depto_id');
            $table->unsignedBigInteger('factor_riesgo_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('area_depto_id')->references('id')->on('area_departamento_emps');
            $table->foreign('factor_riesgo_id')->references('id')->on('factor_riesgos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_depto_riesgos');
    }
};
