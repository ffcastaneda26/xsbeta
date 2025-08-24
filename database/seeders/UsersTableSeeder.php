<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creando Usuarios'));

        User::factory()->create([
            'name' => 'Administrador General',
            'email' => 'super_admin@xsbeta.mx',
            "password" => bcrypt("superadminxsbeta"),
             "active" => 1,
        ])->assignRole('Super Admin');


        User::create([
            "name" => "Administrador del Sistema",
            "email" => "admin@xsbeta.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Administrador');

        User::create([
            "name" => "Usuario General",
            "email" => "general@xsbeta.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ]);
    }
}
