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
        Schema::create('orden_lab_jornadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_lab_id');
            $table->unsignedBigInteger('jornada_id');
            $table->foreign('orden_lab_id')->references('id')->on('orden_labs');
            $table->foreign('jornada_id')->references('id')->on('jornadas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_lab_jornadas');
    }
};
