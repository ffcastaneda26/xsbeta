<?php

use App\Models\AccountingSingleAccount;
use App\Models\AccountSubType;
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
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade')->comment('Empresa');
            $table->foreignIdFor(AccountType::class)->constrained()->onDelete('cascade')->comment('Tipo');
            $table->foreignIdFor(AccountSubType::class)->constrained()->onDelete('cascade')->comment('Sub Tipo');
            $table->foreignIdFor(AccountingSingleAccount::class)->nullable()->constrained()->onDelete('set null')->comment('Cuenta Única');
            $table->string('code',50)->unique();
            $table->string('name',100)->comment('Nombre de la Cuenta');
            $table->text('description')->nullable()->comment('Descripción');
            $table->boolean('is_analysis_code')->default(false);
            $table->boolean('is_cost_center_required')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable()->default(null)->comment('Cuenta padre');
            $table->foreign('parent_id')->references('id')->on('accounting_accounts')->onDelete('set null');

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
