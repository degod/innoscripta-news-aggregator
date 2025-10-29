<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mockery;
use App\Services\JwtAuthService;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        // Mock JwtAuthService
        $jwtMock = Mockery::mock(JwtAuthService::class);
        $jwtMock->shouldReceive('createToken')->andReturn('fake-login-token');
        $this->app->instance(JwtAuthService::class, $jwtMock);

        $payload = ['email' => 'jane@example.com', 'password' => 'password'];

        $response = $this->postJson(route('auth.login'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['access_token', 'token_type', 'user']]);
    }
}
