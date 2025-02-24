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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('estado',20);
            $table->string('codigo_empleado',25);
            $table->string('nombre',200);
            $table->string('telefono',25);
            $table->text('motivo');
            $table->string('fecha_cita',15);
            $table->string('hora_cita',15);
            $table->string('fecha',15);
            $table->string('hora',15);
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
