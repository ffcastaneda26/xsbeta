<?php

namespace App\Filament\Company\Resources\AccountingAccountResource\Pages;

use App\Filament\Company\Resources\AccountingAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountingAccounts extends ListRecords
{
    protected static string $resource = AccountingAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
