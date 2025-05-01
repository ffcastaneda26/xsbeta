<?php

use App\Models\Country;
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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->comment('País al que pertenece el impuesto');
            $table->string('name',20)->comment('Nombre del impuesto');
            $table->integer('min_length')->comment('Mínimo de caracteres');
            $table->integer('max_length')->comment('Mínimo de caracteres');
            $table->string('regex')->comment('Reglas de validación');
            $table->string('description')->nullable()->default(null)->comment('Descripción del impuesto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
