<?php

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
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->comment('Empresa');
            $table->unsignedBigInteger('exercise_id')->comment('Ejercicio');
            $table->integer('month')->comment('Mes contable');
            $table->smallInteger('folio')->default(0)->comment('Folio para voucher o póliza');
            $table->boolean('processed')->default(false)->comment('¿Ya se procesó');
            $table->boolean('active')->default(false)->comment('¿Activo?');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('exercise_id')->references('id')->on('accounting_exercises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_periods');
    }
};
