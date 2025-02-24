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
        Schema::create('resultado_examen_imgs', function (Blueprint $table) {
            $table->id();
            $table->string('fecha',15);
            $table->string('hora',15);
            $table->string('cat_examen',50);
            $table->string('resultado');
            $table->string('url_file',250);
            $table->string('type_file',100);
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');

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
        Schema::dropIfExists('resultado_examen_imgs');
    }
};
