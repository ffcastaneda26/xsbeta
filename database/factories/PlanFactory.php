<?php

namespace Database\Factories;

use App\Enums\PlanTypeEnum;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // Genera un nombre de 3 palabras
            'price' => $this->faker->randomFloat(2, 10, 100), // Precio entre 10.00 y 100.00
            'currency' => $this->faker->currencyCode(), // Código de moneda (ej. USD, EUR)
            'plan_type' => $this->faker->randomElement(PlanTypeEnum::cases()), // Selecciona un valor de la enumeración
            'days' => $this->faker->numberBetween(30, 365), // Días entre 7 y 365
            'description' => $this->faker->paragraph(), // Descripción de un párrafo
            'image' => $this->faker->imageUrl(640, 480, 'plans'), // URL de imagen falsa
            'active' => $this->faker->boolean(), // true o false aleatoriamente
        ];
    }
}
