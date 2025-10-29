<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Str;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_find_user_by_email()
    {
        $user = User::factory()->create([
            'email' => 'repo@example.com'
        ]);

        $repo = $this->app->make(UserRepository::class);
        $found = $repo->findByEmail('repo@example.com');

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_it_can_create_user()
    {
        $repo = $this->app->make(UserRepository::class);

        $data = [
            'first_name' => 'Repo',
            'last_name' => 'User',
            'email' => 'create@example.com',
            'password' => bcrypt('password'),
        ];

        $created = $repo->create($data);

        $this->assertDatabaseHas('users', ['email' => 'create@example.com']);
        $this->assertNotNull($created->uuid);
    }
}
