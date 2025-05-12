<?php

namespace App\Filament\Company\Resources\AccountSubtypeResource\Pages;

use App\Filament\Company\Resources\AccountSubtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountSubtypes extends ListRecords
{
    protected static string $resource = AccountSubtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
