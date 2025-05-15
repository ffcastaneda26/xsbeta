<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            // Tabla creada sin columnas
        });

        Schema::create('states', function (Blueprint $table) {
            // Tabla creada sin columnas
        });

        Schema::create('cities', function (Blueprint $table) {
            // Tabla creada sin columnas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
    }
};
