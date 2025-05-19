<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('labels_by_country', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->comment('País');
            $table->string('use_to', 20)->comment('Usar Para');
            $table->string('value')->comment('Valor');
            $table->string('description')->nullable()->default(null)->comment('Descripción');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels_by_country');
    }
};
