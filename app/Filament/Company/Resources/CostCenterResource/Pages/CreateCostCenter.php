<?php

namespace App\Filament\Company\Resources\CostCenterResource\Pages;

use App\Filament\Company\Resources\CostCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCostCenter extends CreateRecord
{
    protected static string $resource = CostCenterResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
