<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando un Blog de Ejemplo');


        $sql="INSERT INTO blogs  VALUES
            (1, 'Primer Blog', 'primer-blog', 'Subtítulo', 1, 1, '2024-12-20 10:02:00', 2, 'Introduccion ', '	Descripcion general del blog', 1, 'uploads/blogs/primer-blog_blog1.jpg', '2024-12-20 16:45:27', '2024-12-20 16:50:26',null);";


         DB::update($sql);

         $this->command->info('Blog de Ejemplo Creado');

    }
}
