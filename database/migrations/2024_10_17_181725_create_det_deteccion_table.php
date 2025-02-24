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
        Schema::create('det_deteccion', function (Blueprint $table) {
            $table->id();
            $table->string('categoria', 150)->nullable()->default('');
            $table->integer('riesgo_id')->nullable();
            $table->unsignedBigInteger('examen_id');
            $table->integer('detecciontemprana_id');
            $table->unsignedBigInteger('empresa_id');
            
            // Foreign keys
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_deteccion');
    }
};
