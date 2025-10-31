<?php

namespace Tests\Feature\Services;

use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\NewsAggregatorService;
use Mockery;

class NewsAggregatorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_news_and_stores_in_articles_table()
    {
        $mockProvider = Mockery::mock(\App\Services\News\NewsProviderInterface::class);
        $mockProvider->shouldReceive('name')->andReturn('MockProvider');
        $mockProvider->shouldReceive('fetch')->andReturn([
            new \App\Services\News\ProviderArticleDTO(
                source: 'MockProvider',
                author: 'Test Author',
                category: 'Tech',
                title: 'Test Article',
                description: 'Desc',
                content: 'Full content',
                url: 'https://example.com/article',
                urlToImage: null,
                publishedAt: now()->toISOString(),
                metadata: []
            )
        ]);

        $service = new NewsAggregatorService(
            $this->app->make(ArticleRepositoryInterface::class),
            $mockProvider
        );
        $service->orchestrate();

        // Assert it created the article in DB
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'source' => 'MockProvider',
        ]);
    }
}
