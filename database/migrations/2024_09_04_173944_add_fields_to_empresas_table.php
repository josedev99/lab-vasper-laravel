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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('celular', 20)->nullable()->after('telefono');
            $table->string('logo')->nullable()->after('celular');
            $table->string('rubro')->nullable()->after('logo');
            $table->date('fecha_creacion')->nullable()->after('rubro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['celular', 'logo', 'rubro', 'fecha_creacion']);
        });
    }
};
