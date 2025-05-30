<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\Pages;

use App\Filament\Company\Resources\AccountingMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountingMovements extends ListRecords
{
    protected static string $resource = AccountingMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
