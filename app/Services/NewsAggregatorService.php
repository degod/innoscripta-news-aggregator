<?php

namespace App\Services;

use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Source\SourceRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsAggregatorService
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private SourceRepositoryInterface $sourceRepository,
    ) {}

    public function fetchFromNewsAPI(): void
    {
        $apiKey = config('services.newsapi.key');
        $response = Http::get("https://newsapi.org/v2/top-headlines", [
            'country' => 'us',
            'pageSize' => 10,
            'apiKey' => $apiKey,
        ]);

        if (! $response->successful()) {
            return;
        }

        $data = $response->json();

        foreach ($data['articles'] as $articleData) {
            if ($this->articleRepository->existsByUrl($articleData['url'])) {
                continue;
            }

            $this->articleRepository->create([
                'source' => $articleData['source']['name'],
                'author' => $articleData['author'],
                'title' => $articleData['title'],
                'description' => $articleData['description'],
                'content' => $articleData['content'],
                'url' => $articleData['url'] ?? null,
                'url_to_image' => $articleData['urlToImage'] ?? null,
                'published_at' => $articleData['publishedAt'],
            ]);
        }
    }
}
