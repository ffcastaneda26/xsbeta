<?php

namespace App\Filament\Company\Resources\AccountingSingleAccountResource\Pages;

use App\Filament\Company\Resources\AccountingSingleAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountingSingleAccounts extends ListRecords
{
    protected static string $resource = AccountingSingleAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
