<?php

namespace App\Filament\Company\Resources\AccountingPeriodResource\Pages;

use App\Filament\Company\Resources\AccountingPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountingPeriods extends ListRecords
{
    protected static string $resource = AccountingPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
