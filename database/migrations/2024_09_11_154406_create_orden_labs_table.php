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
        Schema::create('orden_labs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden',50);
            $table->string('fecha',15);
            $table->string('hora',15);
            $table->string('estado',20);
            $table->string('estado_eval',20);
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_labs');
    }
};
