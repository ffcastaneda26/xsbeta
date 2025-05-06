<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



    protected function afterCreate(): void
    {
        // Obtener el usuario recién creado
        $user = $this->record;

        // Obtener el tenant actual
        $tenant = Filament::getTenant();
        $tenantId = $tenant ? $tenant->id : null;

        // Asociar el usuario a la empresa del tenant
        if ($tenantId) {
            $user->companies()->sync([$tenantId]);
        }
    }
}
