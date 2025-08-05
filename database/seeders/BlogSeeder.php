<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Company;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $authors = $company->authors;
            $categories = $company->categories;

            Blog::factory()->count(50)->create([
                'company_id' => $company->id,
                'author_id' => $authors->random()->id,
            ])->each(function (Blog $blog) use ($categories) {
                if ($categories->isNotEmpty()) {
                    $blog->categories()->attach(
                        $categories->random(rand(1, $categories->count() >= 3 ? 3 : $categories->count()))->pluck('id')->toArray()
                    );
                }
            });
        }
    }
}
