<?php

namespace App\Filament\Company\Resources\AccountingAccountResource\Pages;

use App\Filament\Company\Resources\AccountingAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountingAccount extends EditRecord
{
    protected static string $resource = AccountingAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
