<?php

use App\Models\AccountSubType;
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
        Schema::create('account_subtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade')->comment('Empresa');
            $table->foreignIdFor(AccountType::class)->constrained()->onDelete('cascade')->comment('Tipo');
            $table->string('code', 4)->comment('Código para formar cuenta contable');
            $table->string('name')->comment('Nombre del Subtipo');
            $table->text('description')->nullable()->comment('Descripción');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_subtypes');
    }
};
