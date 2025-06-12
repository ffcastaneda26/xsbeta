<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->comment('Empresa');
            $table->year('year')->comment('Año comercial o fiscal');
            $table->boolean('processed')->default(false)->comment('¿Ya se procesó');
            $table->boolean('active')->default(false)->comment('Activo?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_exercises');
    }
};
