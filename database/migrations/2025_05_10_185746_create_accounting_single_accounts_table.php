<?php

use App\Models\AccountType;
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
        Schema::create('accounting_single_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade')->comment('Empresa');
            $table->foreignIdFor(AccountType::class)->constrained()->onDelete('cascade')->comment('Tipo');
            $table->string('name');
            $table->text('description')->nullable()->comment('Descripción');
            $table->string('code')->nullable()->default(null)->comment('Código');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_single_accounts');
    }
};
