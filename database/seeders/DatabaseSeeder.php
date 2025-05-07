<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->command->warn(PHP_EOL . __('Truncando Tablas'));
        $this->truncateTables([
            'user_roles',
            'role_permissions',
            'user_permissions',
            'company_user',
            'users',
            'roles',
            'permissions',
            'companies',
            'time_zones',
            'taxes',
            'suscriptions',
            'plans',
        ]);
        $this->command->info('Talas Trucadas');


        $this->call([
            AdminUserSeeder::class,
            TimeZoneSeeder::class,
            TaxSeeder::class,
            PlanSeeder::class,
            CompanySeeder::class,
        ]);
    }

    protected function truncateTables(array $tables) {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); // Desactivamos la revisión de claves foráneas
    }
}
