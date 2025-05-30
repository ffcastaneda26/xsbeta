<?php

use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use App\Models\AccountingExercise;
use App\Models\AccountingPeriod;
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
        Schema::create('accounting_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->comment('Empresa');
            $table->foreignIdFor(AccountingExercise::class)->constrained()->cascadeOnDelete()->comment('Ejercicio Contable');
            $table->foreignIdFor(AccountingPeriod::class)->constrained()->cascadeOnDelete()->comment('Periodo Contable');

            $table->enum('type', array_column(VoucherTypeEnum::cases(), 'value'))
                ->default(VoucherTypeEnum::Both->value)
                ->comment('Tipo de Voucher o Póliza');
            $table->enum('document_type', array_column(VoucherDocumentTypeEnum::cases(), 'value'))
                ->nullable()
                ->default(null)
                ->comment('Tipo de Documento(Apertura, Egreso, Ingreso, Traspaso)');
            $table->string('folio', 15)->comment('Folio');
            $table->date('date')->comment('Fecha de la póliza');
            $table->text('glosa')->comment('Concepto');
            $table->decimal('debit', 10, 2)->default(0.00)->comment('Debe');
            $table->decimal('credit', 10, 2)->default(0.00)->comment('Haber');
            $table->decimal('balance', 10, 2)->default(0)->comment('Saldo');

            $table->enum('status', array_column(VoucherStatusEnum::cases(), 'value'))
                ->default(VoucherStatusEnum::CURRENT)
                ->comment('Estado');
            $table->foreignIdFor(User::class)->comment('Usuario que crea el movimiento');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_movements');
    }
};
