<?php

namespace App\Filament\Company\Resources\TransactionStatusResource\Pages;

use App\Filament\Company\Resources\TransactionStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionStatus extends CreateRecord
{
    protected static string $resource = TransactionStatusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
