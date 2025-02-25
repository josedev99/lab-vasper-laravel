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
            $table->unsignedBigInteger('empresa_id')->after('perfil_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('det_perfil_riesgos', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
        });
    }
};
