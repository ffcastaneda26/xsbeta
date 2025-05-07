<?php

use App\Models\AccountType;
use App\Models\Company;
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
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->comment('Empresa');
            $table->foreignIdFor(AccountType::class)->comment('Tipo de cuenta');
            $table->string('code')->unique()->comment('Cuenta contable');
            $table->string('name',100)->comment('Nombre de la cuenta');
            $table->mediumText('description')->nullable()->comment('Descripción de la cuenta');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_accounts');
    }
};
