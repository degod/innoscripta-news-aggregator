<?php

namespace App\Repositories\Article;

use App\Models\Article;

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

    public function findBySource(string $sourceUuid)
    {
        return $this->article->where('source_uuid', $sourceUuid)->get();
    }

    public function existsByUrl(string $url): bool
    {
        return $this->article->where('source_url', $url)->exists();
    }
}
