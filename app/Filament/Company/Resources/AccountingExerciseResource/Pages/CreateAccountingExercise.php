<?php

namespace App\Filament\Company\Resources\AccountingExerciseResource\Pages;

use App\Filament\Company\Resources\AccountingExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountingExercise extends CreateRecord
{
    protected static string $resource = AccountingExerciseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
