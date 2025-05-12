<?php

namespace App\Filament\Company\Resources\AccountingSingleAccountResource\Pages;

use App\Filament\Company\Resources\AccountingSingleAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountingSingleAccount extends EditRecord
{
    protected static string $resource = AccountingSingleAccountResource::class;

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
