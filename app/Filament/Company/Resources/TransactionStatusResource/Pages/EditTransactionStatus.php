<?php

namespace App\Filament\Company\Resources\TransactionStatusResource\Pages;

use App\Filament\Company\Resources\TransactionStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionStatus extends EditRecord
{
    protected static string $resource = TransactionStatusResource::class;

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
