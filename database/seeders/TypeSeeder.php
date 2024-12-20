<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando Catálogo de Tipos');
       
        $sql="INSERT INTO types (id, name, image) VALUES
            (1, 'Comunicado', 'types/comunicado.jpg'),
            (2, 'Noticia', 'types/noticias.jpg'),
            (3, 'Recomendación', 'types/recomendacion.jpg');";

        DB::update($sql);

        $this->command->info('Catálogo de Tipos Creado');

    }
}
