<?php

namespace App\Filament\Company\Resources\AccountingExerciseResource\Pages;

use App\Filament\Company\Resources\AccountingExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountingExercise extends EditRecord
{
    protected static string $resource = AccountingExerciseResource::class;

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
