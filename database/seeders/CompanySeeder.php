<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
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
        $this->command->warn(PHP_EOL . __('Creating') . ' ' . __('Test') . ' '. __('Company') . ' ' .  __('Contuvo Sistema de Contabilidad'));


        $company = Company::create([
            'name' => 'Empresa de Prueba',
            'short' => 'Empresa Prueba',
            'slug' => 'empresa_prueba',
            'url_company' => 'empresa_prueba',
            'tax_id' => '77.755.210-4',
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

        $this->command->info(__('Company created'));

        $this->command->warn(PHP_EOL . __('Creating Test User to Test Company'));

        $user = User::factory()->withPersonalTeam()->create([
            'name' => 'Administrador Empresa de Prueba',
            'email' => 'admin_empresa1@contuvo.com',
            "password" => bcrypt("password"),
        ]);

        $user->companies()->attach($company->id);

    }
}
