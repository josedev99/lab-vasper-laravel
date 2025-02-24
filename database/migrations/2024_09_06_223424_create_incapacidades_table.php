<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incapacidades', function (Blueprint $table) {
            $table->id();
            $table->string('dui', 20);
            $table->string('codigo_empleado', 50);
            $table->string('colaborador', 100);
            $table->string('departamento', 100)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->string('diagnostico', 550)->nullable();
            $table->string('periodo', 50)->nullable();  
            $table->string('motivo', 500)->nullable();
            $table->string('riesgo', 100)->nullable();
            $table->date('fecha_expedicion');
            $table->string('tipo_incapacidad', 50)->nullable();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('empleado_id');
            $table->timestamps();
            
            // Foreign keys (if needed)
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('empleado_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incapacidades');
    }
}
