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
            'products',
            'product_images',
        ]);

        $this->command->warn(PHP_EOL . __('Creando Roles - Permisos y  Usuarios'));

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AuthorSeeder::class);
        $this->call(BlogSeeder::class);
        $productCategories = ProductCategory::factory(5)->create();

        $products = Product::factory(20)
            ->hasImages(fake()->numberBetween(1, 4))
            ->create();

        $products->each(function ($product) use ($productCategories) {
            $product->categories()->attach(
                $productCategories->random(fake()->numberBetween(1, 3))->pluck('id')
            );
        });
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
