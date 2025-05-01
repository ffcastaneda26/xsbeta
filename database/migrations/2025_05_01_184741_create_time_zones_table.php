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
        Schema::create('time_zones', function (Blueprint $table) {
            $table->id();
            $table->string('time_zone',50)->comment('Zona Horaria');
            $table->string('continent',50)->nullable()->default(null)->comment('Continente');
            $table->string('zone',50)->nullable()->default(null)->comment('Zona');
            $table->boolean('use')->default(1)->comment('¿Usar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_zones');
    }
};
