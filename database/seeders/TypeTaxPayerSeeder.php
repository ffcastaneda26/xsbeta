<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\TypeTaxPayer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeTaxPayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Filling out the Table of Taxpayer Types by Country'));
        DB::table('taxes')->truncate();

        // Datos para la tabla taxes
        $records = [
            [
                'country_code' => 'CL',
                'name' => 'Comunidad',
            ],

            [
                'country_code' => 'CL',
                'name' => 'Cooperativa',
            ],
            [
                'country_code' => 'CL',
                'name' => 'Empresa Individual de Responsabilidad Limitada - EIRL',
            ],
            [
                'country_code' => 'CL',
                'name' => 'Empresario Individual',
            ],
            [
                'country_code' => 'CL',
                'name' => 'Fundación o Cooperación',
            ],
            [
                'country_code' => 'CL',
                'name' => 'Socidad Anónima - S.A. (Cerrada)',
            ],
            [
                'country_code' => 'CL',
                'name' => 'Socidad Anónima - S.A.',
                [
                    'country_code' => 'CL',
                    'name' => 'Sociedad de Responsabilidad Limitada - LTDA',
                ],
            ],
            [
                'country_code' => 'CL',
                'name' => 'Sociedad por Acciones - SpA',
            ],

        ];

        foreach ($records as $record) {
            // Buscar el country_id basado en el código del país
            $country = Country::where('iso2', $record['country_code'])->first();

            if ($country) {
                TypeTaxPayer::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $record['name'],
                    ],

                );
            } else {
                \Log::warning("No se encontró el país con código {$tax['country_code']} al intentar poblar la tabla taxes.");
            }
        }


        $this->command->warn(PHP_EOL . __('Tabla de Impuestos x País llenada'));

    }
}
