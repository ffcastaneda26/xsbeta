<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $this->command->warn(PHP_EOL . __('Filling Out the Transaction statements Catalog'));
        $account_types = [
            [
                'company_id' => 1,
                'name' => 'Vigente',
                'description' => 'Sobre el que se trabaja'
            ],
            [
                'company_id' => 1,
                'name' => 'Descuadrado',
                'description' => 'Suma de debe es diferente a suma de haber'
            ],
            [
                'company_id' => 1,
                'name' => 'Pendiente',
                'description' => 'Aún no se procesa'
            ],
            [
                'company_id' => 1,
                'name' => 'Aplicado',
                'description' => 'Se ha procesado y afectado saldo de cuentas'
            ],
        ];

        foreach ($account_types as $account_type) {

            TransactionStatus::create([
                'company_id' => $account_type['company_id'],
                'name' => $account_type['name'],
                'description' => $account_type['description'],
            ]);
        }

        $this->command->warn(PHP_EOL . __('Account Types Catalog has been created'));
    }
}
