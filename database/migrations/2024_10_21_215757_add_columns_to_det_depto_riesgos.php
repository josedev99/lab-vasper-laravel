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
            $table->string('cat_examen',50)->after('categoria')->nullable();
            $table->unsignedBigInteger('examen_id')->after('cat_examen');
            
            $table->renameColumn('area_depto_id','cargo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('det_depto_riesgos', function (Blueprint $table) {
            //
        });
    }
};
