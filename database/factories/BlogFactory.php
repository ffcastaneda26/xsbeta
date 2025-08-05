<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;
        $published = $this->faker->boolean(80);

        return [
            'company_id' => Company::inRandomOrder()->first()->id, // Asigna una compañía existente de forma aleatoria
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text(2000),
            'image' => $this->faker->imageUrl(),
            'is_published' => $published,
            'published_at' => $published ? $this->faker->dateTimeThisYear : null,
        ];
    }
}
