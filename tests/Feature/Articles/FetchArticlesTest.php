<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use DateTimeImmutable;

class FetchArticlesTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create a JWT token
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET'))
        );

        $now = new DateTimeImmutable();
        $token = $config->builder()
            ->issuedBy(env('APP_NAME'))
            ->permittedFor(env('APP_NAME'))
            ->identifiedBy('test_jwt_token', true)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->relatedTo($user->id)
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($config->signer(), $config->signingKey());

        $this->token = $token->toString();
    }

    private function getWithAuth(string $route, array $params = [])
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($route, $params);
    }

    public function test_it_returns_paginated_articles()
    {
        Article::factory()->count(15)->create();

        $response = $this->getWithAuth(route('articles.index'));

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

        $response = $this->getWithAuth(route('articles.index', ['q' => 'Laravel']));

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

        // Non-matching records
        Article::factory()->count(3)->create([
            'source' => 'BBC',
            'category' => 'Health',
            'author' => 'Jane Doe',
        ]);

        $response = $this->getWithAuth(route('articles.index', [
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
