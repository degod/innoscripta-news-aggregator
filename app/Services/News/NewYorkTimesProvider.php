<?php

namespace App\Services\News;

use Illuminate\Support\Facades\Http;

class NewYorkTimesProvider implements NewsProviderInterface
{
    public function name(): string
    {
        return 'New York Times';
    }

    public function fetch(): iterable
    {
        $apiKey = config('services.nytimes.key');
        $response = Http::get('https://api.nytimes.com/svc/topstories/v2/world.json', [
            'api-key' => $apiKey,
        ]);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        $articles = [];

        foreach ($data['results'] ?? [] as $articleData) {
            $articles[] = new ProviderArticleDTO(
                source: self::name(),
                author: $articleData['byline'] ?? null,
                category: $articleData['section'] ?? null,
                title: $articleData['title'] ?? 'Untitled',
                description: $articleData['abstract'] ?? null,
                content: $articleData['abstract'] ?? null,
                url: $articleData['url'] ?? '',
                urlToImage: $articleData['multimedia'][0]['url'] ?? null,
                publishedAt: $articleData['published_date'] ?? now()->toISOString(),
                metadata: $articleData
            );
        }

        return $articles;
    }
}
