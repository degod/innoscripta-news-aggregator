<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use App\Services\JwtAuthService;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token()
    {
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Mock JwtAuthService so test doesn't depend on token implementation
        $jwtMock = Mockery::mock(JwtAuthService::class);
        $jwtMock->shouldReceive('createToken')->andReturn('fake-jwt-token');
        $this->app->instance(JwtAuthService::class, $jwtMock);

        $response = $this->postJson(route('auth.register'), $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure(['success', 'message', 'data' => ['user', 'access_token', 'token_type']]);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
}
