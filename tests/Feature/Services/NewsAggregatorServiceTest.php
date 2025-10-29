<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\NewsAggregatorService;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Source\SourceRepositoryInterface;
use Illuminate\Support\Facades\Http;

class NewsAggregatorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_and_stores_articles_from_newsapi()
    {
        // Mock external API call
        Http::fake([
            'https://newsapi.org/*' => Http::response([
                'status' => 'ok',
                'articles' => [
                    [
                        'source' => ['name' => 'BBC News'],
                        'author' => 'John Doe',
                        'title' => 'Tech innovation rocks 2025',
                        'description' => 'New innovation in AI world',
                        'url' => 'https://bbc.co.uk/tech/innovation',
                        'urlToImage' => 'https://bbc.co.uk/img.jpg',
                        'publishedAt' => '2025-10-29T09:00:00Z',
                        'content' => 'Full article content here',
                    ],
                ],
            ], 200),
        ]);

        $service = $this->app->make(NewsAggregatorService::class);
        $service->fetchFromNewsAPI();

        $this->assertDatabaseHas('articles', [
            'title' => 'Tech innovation rocks 2025',
            'source' => 'BBC News',
        ]);
    }
}
