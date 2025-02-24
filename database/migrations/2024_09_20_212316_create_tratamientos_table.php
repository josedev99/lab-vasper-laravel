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
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',200);
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
        Schema::dropIfExists('tratamientos');
    }
};
