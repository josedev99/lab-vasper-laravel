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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',200);
            $table->string('genero',4);
            $table->string('telefono',25);
            $table->string('codigo_empleado',150);
            $table->string('no_afiliacion',150);
            $table->string('fecha_ingreso',12);
            $table->string('cargo',150);
            $table->string('fecha_nacimiento',12);
            $table->string('fecha',12);
            $table->string('hora');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('area_depto_id');
            $table->integer('usuario_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->foreign('area_depto_id')->references('id')->on('area_departamento_emps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
