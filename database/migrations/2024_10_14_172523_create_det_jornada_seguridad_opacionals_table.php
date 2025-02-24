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
        Schema::create('det_jornada_seg_opacional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('factor_riesgo_id');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('jornada_id')->references('id')->on('jornada_seg_opacional');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_jornada_seguridad_opacionals');
    }
};
