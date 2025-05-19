<?php

namespace App\Filament\Resources\LabelByCountryResource\Pages;

use App\Filament\Resources\LabelByCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLabelByCountry extends EditRecord
{
    protected static string $resource = LabelByCountryResource::class;

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
