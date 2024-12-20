<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


            // create permissions
            $permissions = [

            ];

            if(count($permissions)){
                foreach ($permissions as $permission) {
                    Permission::create(['name' => $permission]);
                }
            }

            // Super Admin con todos los permisos
            $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
            $role = Role::create(['name' => 'Administrador']);

    }
}
