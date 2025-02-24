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
        Schema::create('det_respuesta_encuestas', function (Blueprint $table) {
            $table->id();
            $table->text('respuesta');
            $table->string('nivel',100);
            $table->unsignedBigInteger('pregunta_id');
            $table->unsignedBigInteger('empleado_id');

            $table->foreign('pregunta_id')->references('id')->on('pregunta_encuestas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_respuesta_encuestas');
    }
};
