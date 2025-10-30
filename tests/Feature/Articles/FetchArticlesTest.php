<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_articles()
    {
        Article::factory()->count(15)->create();

        $response = $this->getJson(route('articles.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data',
                    'links',
                    'total'
                ],
            ]);

        $this->assertCount(10, $response->json('data')['data']); // default pagination
    }

    public function test_it_can_filter_by_query()
    {
        Article::factory()->create(['title' => 'Laravel Testing Rocks']);
        Article::factory()->create(['title' => 'Symfony Guide']);

        $response = $this->getJson(route('articles.index', ['q' => 'Laravel']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data')['data']);
        $this->assertEquals('Laravel Testing Rocks', $response->json('data')['data'][0]['title']);
    }

    public function test_it_can_filter_by_source_category_author_and_date()
    {
        $article = Article::factory()->create([
            'source' => 'New York Times',
            'category' => 'Technology',
            'author' => 'John Doe',
            'published_at' => '2025-10-29',
        ]);

        // Add other records that shouldn't match
        Article::factory()->count(3)->create([
            'source' => 'BBC',
            'category' => 'Health',
            'author' => 'Jane Doe',
        ]);

        $response = $this->getJson(route('articles.index', [
            'source' => 'New York Times',
            'category' => 'Technology',
            'author' => 'John Doe',
            'date' => '2025-10-29',
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data')['data']);
        $this->assertEquals($article->id, $response->json('data')['data'][0]['id']);
    }
}
