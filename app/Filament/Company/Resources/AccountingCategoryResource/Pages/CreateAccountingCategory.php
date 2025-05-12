<?php

namespace App\Filament\Company\Resources\AccountingCategoryResource\Pages;

use App\Filament\Company\Resources\AccountingCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountingCategory extends CreateRecord
{
    protected static string $resource = AccountingCategoryResource::class;

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
