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
        Schema::create('pregunta_encuestas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('num_pregunta');
            $table->text('nombre');
            $table->unsignedBigInteger('encuesta_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta_encuestas');
    }
};
