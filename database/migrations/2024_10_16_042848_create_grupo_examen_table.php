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
        Schema::create('grupo_examen', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->nullable()->default('');
            $table->string('descripcion', 150)->nullable()->default('');
            
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');

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
        Schema::dropIfExists('grupo_examen');
    }
};
