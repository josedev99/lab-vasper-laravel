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
        Schema::create('signo_vitales_medidas', function (Blueprint $table) {
            $table->id();
            $table->string('fc_ipm',50)->nullable()->default('');
            $table->string('fr_rpm',50)->nullable()->default('');
            $table->string('pa_ps_pd',50)->nullable()->default('');
            $table->string('temperatura',50)->nullable()->default('');
            $table->string('saturacion',50)->nullable()->default('');
            $table->string('peso_kg',50)->nullable()->default('');
            $table->string('talla_cm',50)->nullable()->default('');
            $table->string('imc',25)->nullable()->default('');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('consulta_id');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('consulta_id')->references('id')->on('consultas')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signo_vitales_medidas');
    }
};
