<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . __('Creando Blogs'));

        $authors = Author::all();
        $categories = Category::all();

        Blog::factory()->count(50)->create([
            'author_id' => function () use ($authors) {
                return $authors->random()->id;
            },
        ])->each(function (Blog $blog) use ($categories) {
            if ($categories->isNotEmpty()) {
                $blog->categories()->attach(
                    $categories->random(rand(1, $categories->count() >= 3 ? 3 : $categories->count()))->pluck('id')->toArray()
                );
            }
        });
    }
}
