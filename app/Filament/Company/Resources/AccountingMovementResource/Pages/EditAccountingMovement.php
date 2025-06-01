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
        $record = $this->record;
        $movements = $record->movements()->get();
        $debitTotal = $movements->sum(fn($movement) => (float) ($movement->debit ?? 0));
        $creditTotal = $movements->sum(fn($movement) => (float) ($movement->credit ?? 0));

        $record->status = ($debitTotal == $creditTotal)
            ? VoucherStatusEnum::PENDING
            : VoucherStatusEnum::INVALID;
        $record->debit = $debitTotal;
        $record->credit = $creditTotal;

        $record->save();
    }
}
