<?php

namespace App\Repositories\Article;

use App\Models\Article;

interface ArticleRepositoryInterface
{
    public function create(array $data): Article;
    public function all();
    public function findByUuid(string $uuid): ?Article;
    public function findBySource(string $sourceUuid);
    public function existsByUrl(string $url): bool;
}
