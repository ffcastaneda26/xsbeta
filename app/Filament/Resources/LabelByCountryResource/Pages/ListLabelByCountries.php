<?php

namespace App\Filament\Resources\LabelByCountryResource\Pages;

use App\Filament\Resources\LabelByCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLabelByCountries extends ListRecords
{
    protected static string $resource = LabelByCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
