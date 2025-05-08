<?php

namespace App\Excellsus\Traits;

trait UserTrait
{

    // TODO:: Agregar lógica en función de que exista un ROL sin empresa y el USUARIO lo tenga
    public function isAdministrator()
    {
        return $this->email == 'admin@contuvo.com';
    }


    public function hasRoleCompany($company_id, $role_nmae)
    {
        return $this->roles->contains('name', $role_nmae) && $this->companies->contains('id', $company_id);
    }


    public function isCompanyManager(): mixed
    {
        return $this->companies->count();

    }


}
