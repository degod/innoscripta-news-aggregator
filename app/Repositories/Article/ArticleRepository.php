<?php

namespace App\Repositories\Article;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private Article $article) {}

    public function create(array $data): Article
    {
        return $this->article->create($data);
    }

    public function all()
    {
        return $this->article->latest('published_at')->get();
    }

    public function findByUuid(string $uuid): ?Article
    {
        return $this->article->where('uuid', $uuid)->first();
    }

    public function findBySource(string $sourceName)
    {
        return $this->article->where('source', $sourceName)->get();
    }

    public function existsByUrl(string $url): bool
    {
        return $this->article->where('url', $url)->exists();
    }

    public function filter(array $filters): LengthAwarePaginator
    {
        return $this->article->newQuery()
            ->when($filters['q'] ?? null, function ($query, $q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('title', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%");
                });
            })
            ->when(
                $filters['category'] ?? null,
                fn($query, $category) =>
                $query->where('category', $category)
            )
            ->when(
                $filters['source'] ?? null,
                fn($query, $source) =>
                $query->where('source', $source)
            )
            ->when(
                $filters['author'] ?? null,
                fn($query, $author) =>
                $query->where('author', $author)
            )
            ->when(
                $filters['date'] ?? null,
                fn($query, $date) =>
                $query->whereDate('published_at', $date)
            )
            ->orderByDesc('published_at')
            ->paginate(10);
    }

    public function filterByPreferences(array $preferences): LengthAwarePaginator
    {
        return $this->article->newQuery()
            ->when(
                !empty($preferences['sources']),
                fn($query) =>
                $query->whereIn('source', $preferences['sources'])
            )
            ->when(
                !empty($preferences['categories']),
                fn($query) =>
                $query->whereIn('category', $preferences['categories'])
            )
            ->when(
                !empty($preferences['authors']),
                fn($query) =>
                $query->whereIn('author', $preferences['authors'])
            )
            ->orderByDesc('published_at')
            ->paginate(10);
    }

    public function getDistinctSources(): Collection
    {
        return $this->article->select('source')->distinct()->pluck('source');
    }

    public function getDistinctCategories(): Collection
    {
        return $this->article->select('category')->whereNotNull('category')->distinct()->pluck('category');
    }

    public function getDistinctAuthors(): Collection
    {
        return $this->article->select('author')->whereNotNull('author')->distinct()->pluck('author');
    }
}
