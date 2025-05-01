<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creating') . ' ' . __('Plans') );

        DB::table('suscriptions')->truncate();
        DB::table('plans')->truncate();


        Plan::create([
            'name' => 'Demostración',
            'price' => 0,
            'currency' => 'USD',
            'plan_type' => 'Monthly',
            'days' => 30,
            'image' => 'https://cdn-icons-png.flaticon.com/512/107/107831.png',
            'active' => true,
        ]);

        $this->command->warn(PHP_EOL . __('Demonstration plan created') );

    }
}
