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

class DistinctAttributesTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Generate a real JWT token
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

    private function getWithAuth(string $route)
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($route);
    }

    public function test_it_gets_distinct_sources()
    {
        Article::factory()->create(['source' => 'CNN']);
        Article::factory()->create(['source' => 'BBC']);
        Article::factory()->create(['source' => 'CNN']); // duplicate

        $response = $this->getWithAuth(route('articles.sources'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['CNN', 'BBC'], $response->json()['data']);
    }

    public function test_it_gets_distinct_categories()
    {
        Article::factory()->create(['category' => 'Tech']);
        Article::factory()->create(['category' => 'Business']);
        Article::factory()->create(['category' => 'Tech']);

        $response = $this->getWithAuth(route('articles.categories'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['Tech', 'Business'], $response->json()['data']);
    }

    public function test_it_gets_distinct_authors()
    {
        Article::factory()->create(['author' => 'John Doe']);
        Article::factory()->create(['author' => 'Jane Doe']);
        Article::factory()->create(['author' => 'John Doe']);

        $response = $this->getWithAuth(route('articles.authors'));
        $response->assertStatus(200);
        $this->assertEqualsCanonicalizing(['John Doe', 'Jane Doe'], $response->json()['data']);
    }
}
