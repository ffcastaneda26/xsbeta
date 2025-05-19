<?php

namespace App\Filament\Company\Resources\AccountingExerciseResource\Pages;

use App\Filament\Company\Resources\AccountingExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountingExercises extends ListRecords
{
    protected static string $resource = AccountingExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
