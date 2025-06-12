<?php

namespace Database\Seeders;

use App\Models\AccountingExercise;
use App\Models\AccountingPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExcerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . 'Llenando tabla de Ejercicios');
        $exercise = AccountingExercise::create([
            'company_id' => 1,
            'year' => '2025'
        ]);

        $this->command->info('Ejercicio Creado');

        $this->command->warn(PHP_EOL . 'Creará los períodos para el ejercicio');

        for ($i=0; $i <12 ; $i++) {
            AccountingPeriod::create([
                'company_id' => 1,
                'exercise_id' => $exercise->id,
                'month'     => $i,
            ]);
        }

        $this->command->warn(PHP_EOL . __('Períodos Creados'));
    }

}
