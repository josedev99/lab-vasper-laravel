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
        Schema::create('result_acido_uricos', function (Blueprint $table) {
            $table->id();
            $table->string('resultado',50);
            $table->string('estado',50);
            $table->text('observaciones');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empleado_id');

            $table->unsignedBigInteger('empresa_id');

            $table->foreign('jornada_id')->references('id')->on('jornadas');
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
        Schema::dropIfExists('result_acido_uricos');
    }
};
