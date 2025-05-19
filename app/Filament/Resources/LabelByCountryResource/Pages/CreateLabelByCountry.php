<?php

namespace App\Filament\Resources\LabelByCountryResource\Pages;

use App\Filament\Resources\LabelByCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLabelByCountry extends CreateRecord
{
    protected static string $resource = LabelByCountryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
