<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        // Crea los roles para suscriptor y para usuarios en lo general de la empresa
        // $this->create_role($company,env('APP_ROL_TO_SUSCRIPTOR','Suscriptor'));
        // $this->create_role($company,env('APP_ROL_TO_GENERAL_USER','General'));
    }


    /**
     * Crea rol paral a empresa
    */
    public function create_role(Company $company,$role_name)
    {
        $role = $company->roles()->where('name', $role_name)->first();
        if (!$role) {
            $company->roles()->create([
                'company_id' => $company->id,
                'name' => $role_name,
            ]);
        }
    }
}
