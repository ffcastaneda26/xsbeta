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

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $activeExercise = AccountingExercise::where('active', 1)
            ->where('company_id', filament()->getTenant()->id)
            ->first();
        $activePeriod = AccountingPeriod::where('active', 1)
            ->where('exercise_id', $activeExercise?->id)
            ->where('company_id', filament()->getTenant()->id)
            ->first();

        $year = $activeExercise?->year ?? date('Y');
        $month = str_pad($activePeriod?->month ?? date('m'), 2, '0', STR_PAD_LEFT);
        $folio = str_pad(filament()->getTenant()->folio +1 ?? 0, 4, '0', STR_PAD_LEFT);

        $folioString = $year . $month . $folio;

        $data['accounting_exercise_id'] = $activeExercise->id;
        $data['accounting_period_id'] = $activePeriod->id;
        $data['status'] = VoucherStatusEnum::PENDING;
        $data['user_id'] = Auth::user()->id;
        $data['folio'] = $folioString;
        return $data;
    }

    protected function afterCreate(): void
    {
        // Get the active tenant
        $tenant = filament()->getTenant();

        // Increment the tenant's folio attribute
        $tenant->folio = ($tenant->folio ?? 0) + 1;

        // Save the updated tenant
        $tenant->save();
    }
}
