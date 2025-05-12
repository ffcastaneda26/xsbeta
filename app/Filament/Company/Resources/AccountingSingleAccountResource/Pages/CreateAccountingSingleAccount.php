<?php

namespace App\Filament\Company\Resources\AccountingSingleAccountResource\Pages;

use App\Filament\Company\Resources\AccountingSingleAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountingSingleAccount extends CreateRecord
{
    protected static string $resource = AccountingSingleAccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
