<?php

namespace App\Repositories\Category;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function all();
    public function create(array $data): Category;
    public function findBySlug(string $slug): ?Category;
    public function findByUuid(string $uuid): ?Category;
}
