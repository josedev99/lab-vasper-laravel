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
        Schema::create('jornada_seg_opacional', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',200);
            $table->string('fecha',15);
            $table->string('hora',15);
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jornada_seguridad_opacionals');
    }
};
