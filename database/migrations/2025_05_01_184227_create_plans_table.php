<?php

use App\Enums\IntervalPlanEnum;
use App\Enums\PlanTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nombre del Plan');
            $table->decimal('price', 10, 2)->comment('Precio del Plan');
            $table->string('currency', 10)->default('USD')->comment('Moneda del Plan');
            $table->enum('plan_type', array_column(PlanTypeEnum::cases(), 'value'))
            ->default(PlanTypeEnum::Monthly->value)->comment('Tipo (periódo)');

            $table->integer('days')->default(30)->comment('Cantidad de intervalos de facturación del Plan');
            $table->text('description')->nullable()->comment('Descripción del Plan');
            $table->string('image')->nullable()->comment('Imagen del Plan');
            $table->boolean('active')->default(true)->comment('Estado del Plan');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
