<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creando Roles - Permisos'));

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('user_permissions')->truncate();
        DB::table('user_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        // Permisos
        $permissions = [

        ];

        if (count($permissions)) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }
        }

        $roles = [
            'Super Admin',
            'Administrador',
        ];


        if (count($roles)) {
            foreach ($roles as $role) {
                Role::create(['name' => $role]);
            }
        }

    }
}
