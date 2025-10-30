<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'source' => $this->faker->name(),
            'author' => $this->faker->name(),
            'category' => $this->faker->name(),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->paragraph(6),
            'url' => $this->faker->unique()->url(),
            'url_to_image' => $this->faker->imageUrl(),
            'published_at' => now(),
            'metadata' => ['tags' => ['AI', 'Tech']],
        ];
    }
}
