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
        Schema::table('det_depto_riesgos', function (Blueprint $table) {
            $table->dropForeign('det_depto_riesgos_area_depto_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('det_depto_riesgos', function (Blueprint $table) {
            $table->foreign('area_depto_id')->references('id')->on('area_departamento_emps');
        });
    }
};
