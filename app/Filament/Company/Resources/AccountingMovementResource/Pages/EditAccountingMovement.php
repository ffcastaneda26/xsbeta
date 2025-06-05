<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\Pages;

use App\Enums\VoucherStatusEnum;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Company\Resources\AccountingMovementResource;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class EditAccountingMovement extends EditRecord
{
    protected static string $resource = AccountingMovementResource::class;

    #[On('refresh-relation-manager')]
    public function refreshForm()
    {
        $this->refreshFormData([
            'type',
            'document_type',
            'date',
            'glosa',
            // Add other fields if needed
        ]);

    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
    protected function afterSave(): void
    {
        $record = $this->record;
        $record->calculateTotals();
        $record->updateStatus();
    }


}
