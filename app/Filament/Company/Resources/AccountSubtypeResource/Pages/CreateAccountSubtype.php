<?php

namespace App\Filament\Company\Resources\AccountSubtypeResource\Pages;

use App\Filament\Company\Resources\AccountSubtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountSubtype extends CreateRecord
{
    protected static string $resource = AccountSubtypeResource::class;

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
