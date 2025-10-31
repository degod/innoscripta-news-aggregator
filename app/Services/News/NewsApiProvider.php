<?php

namespace App\Services\News;

use Illuminate\Support\Facades\Http;

class NewsApiProvider implements NewsProviderInterface
{
    public function name(): string
    {
        return 'NewsAPI';
    }

    public function fetch(): iterable
    {
        $apiKey = config('services.newsapi.key');
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'country' => 'us',
            'pageSize' => 10,
            'apiKey' => $apiKey,
        ]);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        $articles = [];

        foreach ($data['articles'] ?? [] as $articleData) {
            $articles[] = new ProviderArticleDTO(
                source: $articleData['source']['name'] ?? self::name(),
                author: $articleData['author'] ?? null,
                category: 'general',
                title: $articleData['title'] ?? 'Untitled',
                description: $articleData['description'] ?? null,
                content: $articleData['content'] ?? null,
                url: $articleData['url'] ?? '',
                urlToImage: $articleData['urlToImage'] ?? null,
                publishedAt: $articleData['publishedAt'] ?? now()->toISOString(),
                metadata: $articleData
            );
        }

        return $articles;
    }
}
