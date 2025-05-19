<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\LabelByCountry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelByCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->command->warn(PHP_EOL . __('Filling out label catalog by country'));
        DB::table('labels_by_country')->truncate();

        $records = [
            [
                'country_code' => 'CL',
                'use_to' => 'poliza',
                'value' => 'Voucher',
            ],
            [
                'country_code' => 'CL',
                'use_to' => 'polizas',
                'value' => 'Vouchers',
            ],
            [
                'country_code' => 'CL',
                'use_to' => 'debe',
                'value' => 'Debe',
            ],
            [
                'country_code' => 'CL',
                'use_to' => 'haber',
                'value' => 'Haber',
            ],
            [
                'country_code' => 'MX',
                'use_to' => 'polizas',
                'value' => 'Pólizas',
            ],
            [
                'country_code' => 'MX',
                'use_to' => 'debe',
                'value' => 'Cargo',
            ],
            [
                'country_code' => 'MX',
                'use_to' => 'haber',
                'value' => 'Abono',
            ],
        ];


        foreach ($records as $record) {
            $country = Country::where('iso2', $record['country_code'])->first();
            if ($country) {
                LabelByCountry::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'use_to' => $record['use_to'],
                    ],
                    [
                        'value' => $record['value'],
                    ]
                );
            } else {
                \Log::warning(__('Country code not found' . ' ' . $record['country_code']));

            }
        }

        $this->command->warn(PHP_EOL . __('Completed country label catalog'));
    }
}
