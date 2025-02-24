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
        Schema::table('det_perfil_riesgos', function (Blueprint $table) {
            $table->string('categoria',100)->after('riesgo_id')->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('det_perfil_riesgos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
