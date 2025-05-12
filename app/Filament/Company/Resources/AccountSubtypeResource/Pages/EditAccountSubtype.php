<?php

namespace App\Filament\Company\Resources\AccountSubtypeResource\Pages;

use App\Filament\Company\Resources\AccountSubtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountSubtype extends EditRecord
{
    protected static string $resource = AccountSubtypeResource::class;

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
