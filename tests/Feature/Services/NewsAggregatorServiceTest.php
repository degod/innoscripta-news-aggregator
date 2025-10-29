<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\NewsAggregatorService;
use Illuminate\Support\Facades\Http;

class NewsAggregatorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_and_stores_articles_from_newsapi()
    {
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

    public function test_it_fetches_and_stores_articles_from_new_york_times()
    {
        Http::fake([
            'https://api.nytimes.com/*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'section' => 'world',
                        'byline' => 'By Jane Doe',
                        'title' => 'Global Economy on the Rise',
                        'abstract' => 'The global economy is recovering faster than expected.',
                        'url' => 'https://www.nytimes.com/2025/10/29/world/economy.html',
                        'multimedia' => [
                            ['url' => 'https://www.nytimes.com/image1.jpg']
                        ],
                        'published_date' => '2025-10-29T09:00:00Z',
                    ],
                ],
            ], 200),
        ]);

        $service = $this->app->make(NewsAggregatorService::class);
        $service->fetchFromNewYorkTimes();

        $this->assertDatabaseHas('articles', [
            'title' => 'Global Economy on the Rise',
            'source' => 'New York Times',
        ]);
    }

    public function test_it_fetches_and_stores_articles_from_the_guardian()
    {
        Http::fake([
            'https://content.guardianapis.com/*' => Http::response([
                'response' => [
                    'status' => 'ok',
                    'results' => [
                        [
                            'id' => 'world/2025/oct/29/guardian-innovation-news',
                            'type' => 'article',
                            'sectionName' => 'World news',
                            'webTitle' => 'Guardian Innovation Rocks 2025',
                            'webUrl' => 'https://www.theguardian.com/world/2025/oct/29/guardian-innovation-news',
                            'webPublicationDate' => '2025-10-29T10:00:00Z',
                            'fields' => [
                                'byline' => 'John Smith',
                                'trailText' => 'The Guardian covers AI innovation trends.',
                                'bodyText' => 'Full Guardian article content here.',
                                'thumbnail' => 'https://guardian.co.uk/image.jpg'
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = $this->app->make(NewsAggregatorService::class);
        $service->fetchFromTheGuardian();

        $this->assertDatabaseHas('articles', [
            'title' => 'Guardian Innovation Rocks 2025',
            'source' => 'The Guardian',
        ]);
    }
}
