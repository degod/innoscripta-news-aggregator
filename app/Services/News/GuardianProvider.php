<?php

namespace App\Services\News;

use Illuminate\Support\Facades\Http;

class GuardianProvider implements NewsProviderInterface
{
    public function name(): string
    {
        return 'The Guardian';
    }

    public function fetch(): iterable
    {
        $apiKey = config('services.guardian.key');
        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => $apiKey,
            'show-fields' => 'byline,trailText,bodyText,thumbnail',
            'page-size' => 10,
        ]);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        $articles = [];

        foreach ($data['response']['results'] ?? [] as $articleData) {
            $fields = $articleData['fields'] ?? [];
            $articles[] = new ProviderArticleDTO(
                source: self::name(),
                author: $fields['byline'] ?? null,
                category: $articleData['sectionId'] ?? null,
                title: $articleData['webTitle'] ?? 'Untitled',
                description: $fields['trailText'] ?? null,
                content: $fields['bodyText'] ?? null,
                url: $articleData['webUrl'] ?? '',
                urlToImage: $fields['thumbnail'] ?? null,
                publishedAt: $articleData['webPublicationDate'] ?? now()->toISOString(),
                metadata: $articleData
            );
        }

        return $articles;
    }
}
