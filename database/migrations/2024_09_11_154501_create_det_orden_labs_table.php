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
        Schema::create('det_orden_labs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden',50);
            $table->string('estado',20);
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('orden_lab_id');

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('categoria_id')->references('id')->on('categoria_examens');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->foreign('orden_lab_id')->references('id')->on('orden_labs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_orden_labs');
    }
};
