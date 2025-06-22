<?php

use App\Models\AccountingAccount;
use App\Models\AccountingPeriod;
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
        Schema::create('period_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade')->comment('Empresa');
            $table->foreignIdFor(AccountingPeriod::class)->constrained()->onDelete('cascade')->comment('Período contable');
            $table->foreignIdFor(AccountingAccount::class)->constrained()->onDelete('cascade')->comment('Cuenta contable');
            $table->decimal('debit', 15, 2)->default(0)->comment('Saldo deudor');
            $table->decimal('credit', 15, 2)->default(0)->comment('Saldo acreedor');
            $table->decimal('balance', 15, 2)->default(0)->comment('Saldo final');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_balances');
    }
};