<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creating Admin User') );
        User::factory()->withPersonalTeam()->create([
            'name' => 'Administrador General',
            'email' => 'admin@xsbeta.com',
            "password"  => bcrypt("adminxsbeta"),
        ]);

        $this->command->warn(PHP_EOL . __('Admin User was created') );
    }
}
