<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\Pages;

use App\Enums\VoucherStatusEnum;
use App\Filament\Company\Resources\AccountingMovementResource;
use App\Models\AccountingExercise;
use App\Models\AccountingPeriod;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAccountingMovement extends CreateRecord
{
    protected static string $resource = AccountingMovementResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $company = filament()->getTenant();
        $company->updateFolio();


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
