<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_error_when_no_preference_provided()
    {
        $response = $this->postJson(route('articles.preferences'), []);

        $response->assertStatus(422);
    }

    public function test_it_returns_articles_based_on_preferences()
    {
        $preferredArticle = Article::factory()->create([
            'source' => 'The Guardians',
            'category' => 'Politick',
            'author' => 'Alice Doet',
        ]);

        // non-matching articles
        Article::factory()->count(2)->create([
            'source' => 'BBC',
            'category' => 'Sports',
            'author' => 'Bob Smith',
        ]);

        $response = $this->postJson(route('articles.preferences'), [
            'sources' => ['The Guardians'],
            'categories' => ['Politick'],
            'authors' => ['Alice Doet'],
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data')['data']);
        $this->assertEquals($preferredArticle->id, $response->json('data')['data'][0]['id']);
    }
}
