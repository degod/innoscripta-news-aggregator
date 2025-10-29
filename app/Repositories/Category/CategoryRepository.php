<?php

namespace App\Repositories\Category;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Category $category) {}

    public function all()
    {
        return $this->category->latest()->get();
    }

    public function create(array $data): Category
    {
        return $this->category->create($data);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->category->where('slug', $slug)->first();
    }

    public function findByUuid(string $uuid): ?Category
    {
        return $this->category->where('uuid', $uuid)->first();
    }
}
