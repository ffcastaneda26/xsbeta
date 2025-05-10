<?php

namespace Database\Seeders;

use App\Models\AccountSubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Filling Out the Account Sub Types Catalog'));
        $account_subtypes = [
            ['account_type_id' => 1, 'name' => '01 ACTIVO CIRCULANTE'],
            ['account_type_id' => 1, 'name' => '02 ACTIVO FIJO'],
            ['account_type_id' => 1, 'name' => '03 OTROS ACTIVOS'],
            ['account_type_id' => 1, 'name' => '04 CUENTAS DE ORDEN'],
            ['account_type_id' => 2, 'name' => '02 PASIVO CIRCULANTE'],
            ['account_type_id' => 3, 'name' => '01 CAPITAL'],
            ['account_type_id' => 3, 'name' => '20 RESERVAS DE REVALORIZAC'],
            ['account_type_id' => 3, 'name' => '40 UTILIDAD (PERDIDAS)'],
            ['account_type_id' => 4, 'name' => '03 COSTOS DE REMUNERACION'],
            ['account_type_id' => 4, 'name' => '04 COSTO DE EXPLOTACION'],
            ['account_type_id' => 4, 'name' => '05 GASTOS DE ADMINISTRACION'],
            ['account_type_id' => 4, 'name' => '06 GASTOS FINANCIEROS'],
            ['account_type_id' => 4, 'name' => '07 GASTOS DE VENTAS'],
            ['account_type_id' => 4, 'name' => '08 NO OPERACIONALES'],
            ['account_type_id' => 4, 'name' => '40 IMPUESTO A LA RENTA'],
            ['account_type_id' => 5, 'name' => '05 INGRESOS DE EXPLOTACION'],
            ['account_type_id' => 5, 'name' => '06 INGRESOS NO OPERACIONAL'],
            ['account_type_id' => 5, 'name' => '07 REAJUSTES'],
        ];

        foreach ($account_subtypes as $account_sub_type) {

            // dd($account_type['name'] . '-->' . $account_type['description']);
            AccountSubType::create([
                'company_id' => 1,
                'account_type_id' => $account_sub_type['account_type_id'],
                'name' => $account_sub_type['name']
            ]);
        }

        $this->command->warn(PHP_EOL . __('Account Sub Types Catalog has been created'));


    }
}
