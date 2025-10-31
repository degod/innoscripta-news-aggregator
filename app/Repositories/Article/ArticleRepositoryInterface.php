<?php

namespace App\Repositories\Article;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function create(array $data): Article;
    public function all();
    public function findByUuid(string $uuid): ?Article;
    public function findBySource(string $sourceName);
    public function existsByUrl(string $url): bool;

    public function filter(array $filters, int $perPage = 10): LengthAwarePaginator;
    public function filterByPreferences(array $preferences, int $perPage = 10): LengthAwarePaginator;
    public function getDistinctSources(): Collection;
    public function getDistinctCategories(): Collection;
    public function getDistinctAuthors(): Collection;
}
