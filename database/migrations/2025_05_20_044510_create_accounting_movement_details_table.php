<?php

use App\Models\AccountingAccount;
use App\Models\AccountingMovement;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_movement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->comment('Empresa');
            $table->foreignIdFor(AccountingMovement::class)->comment('Movimiento Contable');
            $table->foreignIdFor(AccountingAccount::class)->comment('Cuenta Contable');
            $table->text('glosa')->nullable()->comment('Concepto');
            $table->decimal('debit', 15, 2)->default(0.00)->comment('Debe');
            $table->decimal('credit', 15, 2)->default(0.00)->comment('Haber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_movement_details');
    }
};
