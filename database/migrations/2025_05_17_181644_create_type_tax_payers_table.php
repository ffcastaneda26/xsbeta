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
        Schema::create('type_tax_payers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->comment('País ');
            $table->string('name')->comment('Nombre del tipo de cuenta');
            $table->text('description')->nullable()->comment('Descripción');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_tax_payers');
    }
};
