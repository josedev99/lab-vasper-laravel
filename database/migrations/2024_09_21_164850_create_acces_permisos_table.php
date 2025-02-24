<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acces_permisos', function (Blueprint $table) {
            $table->id();
            
            // Relaciones con las tablas modulos, permisos, empresas, usuarios
            $table->foreignId('id_modulo')->constrained('modulos')->onDelete('cascade');
            $table->foreignId('id_permiso')->constrained('permisos')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acces_permisos');
    }
}
