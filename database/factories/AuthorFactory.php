<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'company_id' => Company::inRandomOrder()->first()->id, // Asigna una compañía existente de forma aleatoria
        ];
    }
}
