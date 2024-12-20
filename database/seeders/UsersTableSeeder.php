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
        User::create([
            "name" => "Super Administrador",
            "email" => "superadmin@xsbeta.com",
            "password" => bcrypt("xsbeta2025"),
            "active" => 1,
        ])->assignRole('Super Admin');

        User::create([
            "name" => "Administrador",
            "email" => "administrador@xsbeta.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Administrador');
        User::create([
            "name" => "Usuario General",
            "email" => "usuario@xsbeta.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Usuario');

    }
}
