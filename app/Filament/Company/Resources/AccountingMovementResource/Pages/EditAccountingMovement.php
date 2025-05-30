<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\Pages;

use App\Enums\VoucherStatusEnum;
use Filament\Actions;
use Livewire\Livewire;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Company\Resources\AccountingMovementResource;

class EditAccountingMovement extends EditRecord
{
    protected static string $resource = AccountingMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

protected function afterSave(): void
    {
        // Get the updated record
        $record = $this->record;

        // Calculate total debit and credit from related movements
        $movements = $record->movements()->get();

        $debitTotal = $movements->sum(fn($movement) => (float) ($movement->debit ?? 0));
        $creditTotal = $movements->sum(fn($movement) => (float) ($movement->credit ?? 0));

        // Update status based on balance
        $record->status = ($debitTotal == $creditTotal)
            ? VoucherStatusEnum::PENDING
            : VoucherStatusEnum::INVALID;

        // Save the updated status
        $record->save();
    }
}
