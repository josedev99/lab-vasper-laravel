<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaciloscopiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_baciloscopia', function (Blueprint $table) {
            $table->id();
            $table->string('resultado', 150)->nullable();
            $table->string('id_jornada', 25)->nullable();
            $table->string('estado', 25)->nullable();
            $table->integer('id_empleado')->nullable();
            $table->string('observacione', 125)->nullable();
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
        Schema::dropIfExists('baciloscopia');
    }
}
