<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * El nombre del modelo que corresponde al factory.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productName = $this->faker->unique()->word() . ' ' . $this->faker->unique()->words(2, true);

        // Genera el array de imágenes de forma programática.
        $imagesList = [];
        for ($i = 1; $i <= 15; $i++) {
            $imagesList[] = 'products/example-' . $i . '.jpg';
        }

        // Selecciona entre 1 y 3 imágenes aleatorias.
        $randomImageCount = $this->faker->numberBetween(1, 3);
        $randomImages = $this->faker->randomElements($imagesList, $randomImageCount);

        return [
            'name' => $productName,
            'slug' => Str::slug($productName),
            'description' => $this->faker->paragraph(4),
            'short_description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(),
            'images' => $randomImages,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            // Adjunta una categoría aleatoria al producto recién creado.
            $category = ProductCategory::inRandomOrder()->first();

            if ($category) {
                $product->categories()->attach($category->id);
            }
        });
    }
}
