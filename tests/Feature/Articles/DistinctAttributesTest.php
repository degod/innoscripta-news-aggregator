<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistinctAttributesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_gets_distinct_sources()
    {
        Article::factory()->create(['source' => 'CNN']);
        Article::factory()->create(['source' => 'BBC']);
        Article::factory()->create(['source' => 'CNN']); // duplicate

        $response = $this->getJson(route('articles.sources'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['CNN', 'BBC'], $response->json()['data']);
    }

    public function test_it_gets_distinct_categories()
    {
        Article::factory()->create(['category' => 'Tech']);
        Article::factory()->create(['category' => 'Business']);
        Article::factory()->create(['category' => 'Tech']);

        $response = $this->getJson(route('articles.categories'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['Tech', 'Business'], $response->json()['data']);
    }

    public function test_it_gets_distinct_authors()
    {
        Article::factory()->create(['author' => 'John Doe']);
        Article::factory()->create(['author' => 'Jane Doe']);
        Article::factory()->create(['author' => 'John Doe']);

        $response = $this->getJson(route('articles.authors'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['John Doe', 'Jane Doe'], $response->json()['data']);
    }
}
