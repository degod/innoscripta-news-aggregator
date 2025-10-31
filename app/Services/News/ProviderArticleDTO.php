<?php

namespace App\Services\News;

use Illuminate\Support\Arr;

class ProviderArticleDTO
{
    public function __construct(
        public readonly string $source,
        public readonly ?string $author,
        public readonly ?string $category,
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $content,
        public readonly string $url,
        public readonly ?string $urlToImage,
        public readonly string $publishedAt,
        public readonly array $metadata = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            source: Arr::get($data, 'source'),
            author: Arr::get($data, 'author'),
            category: Arr::get($data, 'category'),
            title: Arr::get($data, 'title'),
            description: Arr::get($data, 'description'),
            content: Arr::get($data, 'content'),
            url: Arr::get($data, 'url'),
            urlToImage: Arr::get($data, 'url_to_image'),
            publishedAt: Arr::get($data, 'published_at'),
            metadata: Arr::get($data, 'metadata', []),
        );
    }

    public function toArticlePayload(): array
    {
        return [
            'source' => $this->source,
            'author' => $this->author,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'url_to_image' => $this->urlToImage,
            'published_at' => $this->publishedAt,
            'metadata' => $this->metadata,
        ];
    }
}
