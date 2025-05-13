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
        Schema::create('accounting_account_category', function (Blueprint $table) {
            $table->id(); // Optional: Primary key for the pivot table
            $table->foreignId('accounting_account_id')->constrained('accounting_accounts')->onDelete('cascade');
            $table->foreignId('accounting_category_id')->constrained('accounting_categories')->onDelete('cascade');
            // No timestamps() method to exclude created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_account_category');
    }
};
