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
        Schema::table('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_id')->nullable()->after('area_depto_id'); // Asegúrate que cargo_id sea unsignedBigInteger
            $table->foreign('cargo_id')->references('id')->on('cargo_emps')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
