<?php

namespace Database\Seeders;

use App\Models\Tax;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Llenando Tabla de Impuestos x País') );
        DB::table('taxes')->truncate();

        // Datos para la tabla taxes
        $taxes = [
            [
                'country_code' => 'MX',
                'name' => 'RFC',
                'min_length' => 12,
                'max_length' => 13,
                'regex' => '^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$',
                'description' => '12-13 caracteres alfanuméricos (4 letras + 6 dígitos + 3 homoclave)',
            ],
            [
                'country_code' => 'CL',
                'name' => 'RUT',
                'min_length' => 12,
                'max_length' => 13,
                'regex' => '^(?:(\d{1,2})(\.)(\d{3})(\.)(\d{3})-([\dkK])|(\d{7,8})-([\dkK])|(\d{7,8})([\dkK]))$',
                'description' => '8 dígitos + dígito verificador (0-9 o K), con guión',
            ],
            [
                'country_code' => 'AR',
                'name' => 'CUIT',
                'min_length' => 11,
                'max_length' => 11,
                'regex' => '^\d{2}\d{8}\d{1}$',
                'description' => '11 dígitos sin guiones (2 + 8 + 1)',
            ],
            [
                'country_code' => 'PY',
                'name' => 'RUC',
                'min_length' => 11,
                'max_length' => 11,
                'regex' => '^\d{11}$',
                'description' => '11 dígitos',
            ],
            [
                'country_code' => 'CO',
                'name' => 'NIT',
                'min_length' => 9,
                'max_length' => 11,
                'regex' => '^\d{9,11}$',
                'description' => '9-11 dígitos',
            ],
            [
                'country_code' => 'VE',
                'name' => 'RIF',
                'min_length' => 11,
                'max_length' => 11,
                'regex' => '^[VEJGC]\d{8}-\d{1}$',
                'description' => 'Letra (V, E, J, G, C) + 8 dígitos + dígito verificador, con guión',
            ],
        ];

        foreach ($taxes as $tax) {
            // Buscar el country_id basado en el código del país
            $country = Country::where('iso2', $tax['country_code'])->first();

            if ($country) {
                Tax::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $tax['name'],
                    ],
                    [
                        'min_length' => $tax['min_length'],
                        'max_length' => $tax['max_length'],
                        'regex' => $tax['regex'],
                        'description' => $tax['description'],
                    ]
                );
            } else {
                \Log::warning("No se encontró el país con código {$tax['country_code']} al intentar poblar la tabla taxes.");
            }
        }


        $this->command->warn(PHP_EOL . __('Tabla de Impuestos x País llenada') );

    }
}
