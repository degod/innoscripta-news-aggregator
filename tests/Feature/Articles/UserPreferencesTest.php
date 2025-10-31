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

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and issue a JWT
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

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

    private function postWithAuth(string $route, array $payload = [])
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($route, $payload);
    }

    public function test_it_returns_error_when_no_preference_provided()
    {
        $response = $this->postWithAuth(route('articles.preferences'), []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors',
                'message',
            ]);
    }

    public function test_it_returns_articles_based_on_preferences()
    {
        $preferredArticle = Article::factory()->create([
            'source' => 'The Guardians',
            'category' => 'Politick',
            'author' => 'Alice Doet',
        ]);

        // Non-matching articles
        Article::factory()->count(2)->create([
            'source' => 'BBC',
            'category' => 'Sports',
            'author' => 'Bob Smith',
        ]);

        $response = $this->postWithAuth(route('articles.preferences'), [
            'sources' => ['The Guardians'],
            'categories' => ['Politick'],
            'authors' => ['Alice Doet'],
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($preferredArticle->id, $response->json('data')[0]['id']);
    }
}
