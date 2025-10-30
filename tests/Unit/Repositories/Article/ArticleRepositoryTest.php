<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Source;
use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Support\Carbon;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_article()
    {
        $repo = $this->app->make(ArticleRepositoryInterface::class);

        $articleData = [
            'source' => 'BBC News',
            'author' => 'John Doe',
            'title' => 'AI Revolutionizes News',
            'description' => 'How AI is changing journalism.',
            'content' => 'Full article content...',
            'url' => 'https://newsapi.org/article-123',
            'url_to_image' => 'https://newsapi.org/image.jpg',
            'published_at' => Carbon::now(),
            'metadata' => ['source' => 'NewsAPI'],
        ];

        $article = $repo->create($articleData);

        $this->assertDatabaseHas('articles', ['title' => 'AI Revolutionizes News']);
        $this->assertEquals('BBC News', $article->source);
    }
}
