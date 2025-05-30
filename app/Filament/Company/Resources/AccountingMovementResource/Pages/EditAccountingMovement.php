<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\Pages;

use Filament\Actions;
use Livewire\Livewire;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Company\Resources\AccountingMovementResource;

class EditAccountingMovement extends EditRecord
{
    protected static string $resource = AccountingMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
