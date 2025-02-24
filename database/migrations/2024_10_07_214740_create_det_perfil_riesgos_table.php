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
        Schema::create('det_perfil_riesgos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('riesgo_id');
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('perfil_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('det_perfil_riesgos');
    }
};
