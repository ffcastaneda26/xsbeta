<?php

namespace App\Filament\Resources\TypeTaxPayerResource\Pages;

use App\Filament\Resources\TypeTaxPayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeTaxPayer extends EditRecord
{
    protected static string $resource = TypeTaxPayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
