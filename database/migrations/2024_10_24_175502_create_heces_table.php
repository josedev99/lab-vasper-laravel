<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_heces', function (Blueprint $table) {
            $table->id();
            $table->string('id_jornada', 25);
            $table->string('color', 25);
            $table->string('consistencia', 40);
            $table->string('mucus', 40);
            $table->string('macroscopicos', 50);
            $table->string('microscopicos', 50);
            $table->string('hematies', 50);
            $table->string('leucocitos', 50);
            $table->string('activos', 255);
            $table->string('quistes', 255);
            $table->string('metazoarios', 100);
            $table->string('protozoarios', 10);
            $table->string('observaciones', 125);
            $table->string('id_empleado', 50);
            $table->string('estado', 25);
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
        Schema::dropIfExists('heces');
    }
}
