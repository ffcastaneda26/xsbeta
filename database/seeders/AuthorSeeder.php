<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            Author::factory()->count(10)->create([
                'company_id' => $company->id,
            ]);
        }
    }
}
