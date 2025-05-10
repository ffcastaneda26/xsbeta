<?php

namespace Database\Seeders;

use App\Models\AccountType;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Filling Out the Account Type Catalog'));
        FacadesDB::table('account_types')->truncate();
        $account_types = [
            [
                'company_id' => 1,
                'name' => 'Activo',
                'description' => 'Representa los bienes y derechos de la empresa.'
            ],
            [
                'company_id' => 1,
                'name' => 'Pasivo',
                'description' => 'Obligaciones y deudas de la empresa'
            ],
            [
                'company_id' => 1,
                'name' => 'Capital',
                'description' => 'Capital propio de la empresa, compuesto por las aportaciones de los socios y los beneficios acumulados.'
            ],
            [
                'company_id' => 1,
                'name' => 'Ingreso',
                'description' => 'Representan el dinero que entra en la empresa (ejemplo: ventas de productos o servicios)'
            ],
            [
                'company_id' => 1,
                'name' => 'Costo',
                'description' => 'Inversiones que generan beneficios futuros (ejemplo: compra de maquinaria o desarrollo de software)'
            ],
            [
                'company_id' => 1,
                'name' => 'Gasto',
                'description' => 'Son los costes operativos necesarios para el funcionamiento (ejemplo: alquiler, sueldos, suministros)'
            ],
            [
                'company_id' => 1,
                'name' => 'Mixta',
                'description' => 'Su saldo en una fecha determinada está conformada por una parte de cuentas reales y por otra de patriminio o capital'
            ],
            [
                'company_id' => 1,
                'name' => 'Orden',
                'description' => 'Operaciones que no afectan el activo, pasivo o patrimonio, no cambian la estructura del balance general.'
            ],
        ];

        foreach ($account_types as $account_type) {

            // dd($account_type['name'] . '-->' . $account_type['description']);
            AccountType::create([
                'company_id' => $account_type['company_id'],
                'name' => $account_type['name'],
                'description' => $account_type['description'],
            ]);
        }

        $this->command->warn(PHP_EOL . __('Account Types Catalog has been created'));

    }
}
