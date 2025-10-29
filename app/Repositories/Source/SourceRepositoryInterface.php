<?php

namespace App\Repositories\Source;

use App\Models\Source;

interface SourceRepositoryInterface
{
    public function create(array $data): Source;
    public function all();
    public function findByUuid(string $uuid): ?Source;
    public function findByName(string $name): ?Source;
}
