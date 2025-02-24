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
        Schema::create('det_jornadas', function (Blueprint $table) {
            $table->id();
            $table->string('estado',50);
            $table->string('evaluacion',50);
            
            $table->string('cat_examen',50);
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');

            //relacion
            $table->foreign('jornada_id')->references('id')->on('jornadas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
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
        Schema::dropIfExists('det_jornadas');
    }
};
