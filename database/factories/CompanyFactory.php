<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'short' => $this->faker->unique()->companySuffix,
            'slug' => Str::slug($name),
            'rfc' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
            'url_company' => Str::slug($name) . $this->faker->unique()->randomNumber(4),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'address' => $this->faker->streetAddress,
            'num_ext' => $this->faker->buildingNumber,
            'num_int' => $this->faker->secondaryAddress,
            'country_id' => $this->faker->numberBetween(1, 200), // Asegúrate de que existan países en tu base de datos
            'state_id' => $this->faker->numberBetween(1, 32), // Asegúrate de que existan estados en tu base de datos
            'municipality' => $this->faker->city,
            'city' => $this->faker->city,
            'colony' => $this->faker->city,
            'zipcode' => $this->faker->postcode,
            'logo' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean,
            'user_id' => $this->faker->numberBetween(1, 10), // Asegúrate de que existan usuarios en tu base de datos
        ];
    }
}
