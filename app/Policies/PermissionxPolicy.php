<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permissionx;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class PermissionxPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permissionx $permissionx): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permissionx $permissionx): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permissionx $permissionx): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Permissionx $permissionx): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Permissionx $permissionx): bool
    {
       return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');

    }
}
