<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Role;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }



    protected function getCreatedNotificationTitle(): ?string
    {
        return __('Congratulations, your company has been created');
    }

    protected function afterCreate(): void
    {
        // Obtener el registro recién creado (Company)
        $company = $this->record;
        $user = Auth::user();

        // Crear un nuevo rol
    //    Role::create([
    //         'company_id' => $company->id,
    //         'name' => env('APP_ROL_TO_SUSCRIPTOR', 'Suscriptor'),
    //     ]);
    //    Role::create([
    //         'company_id' => $company->id,
    //         'name' => env('APP_ROL_TO_GENERAL_USER', 'General'),
    //     ]);

        // Vincular el rol al usuario autenticado en la tabla user_roles
        $user->roles()->attach($company->roles);
    }
}
