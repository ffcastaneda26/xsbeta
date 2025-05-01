<?php

use App\Enums\Enums\SuscriptionStatusEnum;
use App\Models\Company;
use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->comment('Empresa');
            $table->foreignIdFor(Plan::class)->comment('Plan');
            $table->date('date')->comment('Fecha de la suscripción');
            $table->decimal('amount', 10, 2)->comment('Precio del Plan');
            $table->enum('status', array_column(SuscriptionStatusEnum::cases(), 'value'))
                ->default(SuscriptionStatusEnum::Valid->value)->comment('Estado de la suscripción');

            $table->date('bill_date')->comment('Fecha de facturación');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscriptions');
    }
};
