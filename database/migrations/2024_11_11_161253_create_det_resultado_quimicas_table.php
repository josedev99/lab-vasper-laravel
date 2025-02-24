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
        Schema::create('det_resultado_quimicas', function (Blueprint $table) {
            $table->id();
            $table->string('resultado',50)->nullable()->default('');
            $table->string('estado',150);
            $table->unsignedBigInteger('result_quimica_id');
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('result_quimica_id')->references('id')->on('resultado_quimicas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_resultado_quimicas');
    }
};
