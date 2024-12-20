<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando Catálogo de Categorías');

       $sql="INSERT INTO categories (id, name, image) VALUES
            (1, 'Cultura', 'categories/cultura.jpg'),
            (2, 'Tecnología', 'categories/tecnología.jpg');";


        DB::update($sql);

        $this->command->info('Catálogo de Categorías Creado');
    }
}
