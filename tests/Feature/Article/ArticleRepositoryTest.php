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
        $source = Source::factory()->create();

        $repo = $this->app->make(ArticleRepositoryInterface::class);

        $articleData = [
            'source_uuid' => $source->uuid,
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
        $this->assertEquals($source->uuid, $article->source_uuid);
    }

    public function test_it_fetches_articles_by_source()
    {
        $source = Source::factory()->create();

        $repo = $this->app->make(ArticleRepositoryInterface::class);

        $repo->create([
            'source_uuid' => $source->uuid,
            'title' => 'Tech Today',
            'url' => 'https://example.com/tech',
            'published_at' => now(),
        ]);

        $repo->create([
            'source_uuid' => $source->uuid,
            'title' => 'Sports Roundup',
            'url' => 'https://example.com/sports',
            'published_at' => now(),
        ]);

        $articles = $repo->findBySource($source->uuid);
        $this->assertCount(2, $articles);
    }
}
