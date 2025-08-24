<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
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
        $this->command->info(PHP_EOL . __('Depurando Tablas.....'));

        $this->truncateTables([
            'user_roles',
            'role_permissions',
            'user_permissions',
            'users',
            'roles',
            'permissions',
            'blogs',
            'categories',
            'authors',
            'category_product',
            'product_categories',
            'products'
        ]);

        $this->command->info(PHP_EOL . __('Tablas depuradas...'));

        $this->command->warn(PHP_EOL . __('Inicia la creación de datos de prueba...'));

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AuthorSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);

        $this->command->info(PHP_EOL . __('Se han cargado datos de prueba...'));

    }

    protected function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); // Desactivamos la revisión de claves foráneas
    }
}
