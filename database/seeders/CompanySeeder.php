<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creating') . ' ' . __('Company') . ' ' .  __('Contuvo Sistema de Contabilidad'));
        DB::table('company_user')->truncate();
        DB::table('companies')->truncate();

        $company = Company::create([
            'name' => 'Contuvo Sistema de Contabilidad',
            'short' => 'contuvo',
            'slug' => 'contuvo_sistema_de_contabilidad',
            'url_company' => 'contuvo',
            'tax_id' => '12345678-K',
            'phone' => '5555555555',
            'address' => 'Calle de la Compañia',
            'num_ext' => '123',
            'num_int' => '456',
            'colony' => 'Colonia de la Compañia',
            'zipcode' => '12345',
            'email' => 'ffcastaneda@gmail.com',
            'country_id' => env('APP_DEFAULT_COUNTRY', 44),
            'state_id' => env('APP_DEFAULT_STATE', 2824),
            'city' => 'Santiago',
            'user_id' => 1,
        ]);

        $this->command->info('Company created');
    }
}
