<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $user) {}

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findByUuid(string $uuid): ?User
    {
        return $this->user->where('uuid', $uuid)->first();
    }
}
