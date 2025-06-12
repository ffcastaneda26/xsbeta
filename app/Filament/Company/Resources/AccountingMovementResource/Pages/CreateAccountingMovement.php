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

    protected function afterCreate(): void
    {
        $record = $this->record;
        $record->calculateTotals();
        $record->updateStatus();
        if ($record->period) {
            $record->period->updateFolio();
        }
    }
}
