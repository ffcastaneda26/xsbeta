<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounting_accounts', function (Blueprint $table) {
            $table->decimal('debit', 10, 2)->default(0)->after('parent_id')->comment('Suma del Debe ');
            $table->decimal('credit', 10, 2)->default(0)->after('debit')->comment('Suma del Haber ');
            $table->decimal('balance', 10, 2)->default(0)->after('credit')->comment('Saldo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_accounts', function (Blueprint $table) {
            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('balance');
        });
    }
};
