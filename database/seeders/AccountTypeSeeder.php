<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Filling Out the Account Type Catalog'));
        $account_types = [
            [
                'company_id' => 1,
                'name' => 'Activos',
                'description' => 'Representa los bienes y derechos de la empresa.'
            ],
            [
                'company_id' => 1,
                'name' => 'Pasivos',
                'description' => 'Obligaciones y deudas de la empresa'
            ],
            [
                'company_id' => 1,
                'name' => 'Patrimonio',
                'description' => 'Capital propio de la empresa, compuesto por las aportaciones de los socios y los beneficios acumulados.'
            ],
            [
                'company_id' => 1,
                'name' => 'PERDIDAS/GASTOS',
                'description' => ''
            ],
            [
                'company_id' => 1,
                'name' => 'GANANCIAS/INGRESOS',
                'description' => 'Inversiones que generan beneficios futuros (ejemplo: compra de maquinaria o desarrollo de software)'
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
