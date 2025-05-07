<?php

namespace App\Filament\Resources\AccountTypeResource\Pages;

use App\Filament\Resources\AccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountType extends EditRecord
{
    protected static string $resource = AccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // TODO:: Permitir sólo si el tipo de cuenta no tiene Cuentas Contables en alguna empresa
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
