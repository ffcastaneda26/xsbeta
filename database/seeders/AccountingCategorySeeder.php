<?php

namespace Database\Seeders;

use App\Models\AccountingCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Filling Out the Account Sub Types Catalog'));
        $records = [
            'Compras',
            'Ventas',
            'Honorarios',
            'Banco/Libro Caja',
            'Remuneraciones',
            'Activos Fijos',
            'Tesorería',
            'Fact. Electrónica',
            'Presupuesto',
            'Flujo Caja',

        ];

        foreach ($records as $record) {
            AccountingCategory::create([
                'company_id' => 1,
                'name' => $record
            ]);
        }

        $this->command->warn(PHP_EOL . __('Account Categories Catalog has been created'));
    }
}
