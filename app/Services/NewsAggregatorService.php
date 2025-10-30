<?php

namespace App\Services;

use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsAggregatorService
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
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

    /**
     * Fetch and store articles from the New York Times API
     */
    public function fetchFromNewYorkTimes(): void
    {
        $apiKey = config('services.nytimes.key');
        $response = Http::get("https://api.nytimes.com/svc/topstories/v2/world.json", [
            'api-key' => $apiKey,
        ]);

        if (! $response->successful()) {
            return;
        }

        $data = $response->json();

        foreach ($data['results'] ?? [] as $articleData) {
            if ($this->articleRepository->existsByUrl($articleData['url'])) {
                continue;
            }

            $this->articleRepository->create([
                'uuid' => Str::uuid(),
                'source' => 'New York Times',
                'author' => $articleData['byline'] ?? null,
                'title' => $articleData['title'],
                'description' => $articleData['abstract'] ?? null,
                'content' => null,
                'url' => $articleData['url'],
                'url_to_image' => $articleData['multimedia'][0]['url'] ?? null,
                'published_at' => $articleData['published_date'],
                'metadata' => $articleData,
            ]);
        }
    }

    /**
     * Fetch and store articles from The Guardian API
     */
    public function fetchFromTheGuardian(): void
    {
        $apiKey = config('services.guardian.key');
        $response = Http::get("https://content.guardianapis.com/search", [
            'api-key' => $apiKey,
            'show-fields' => 'byline,trailText,bodyText,thumbnail',
            'page-size' => 10,
        ]);

        if (! $response->successful()) {
            return;
        }

        $data = $response->json();

        foreach ($data['response']['results'] ?? [] as $articleData) {
            if ($this->articleRepository->existsByUrl($articleData['webUrl'])) {
                continue;
            }

            $this->articleRepository->create([
                'uuid' => Str::uuid(),
                'source' => 'The Guardian',
                'author' => $articleData['fields']['byline'] ?? null,
                'title' => $articleData['webTitle'],
                'description' => $articleData['fields']['trailText'] ?? null,
                'content' => $articleData['fields']['bodyText'] ?? null,
                'url' => $articleData['webUrl'],
                'url_to_image' => $articleData['fields']['thumbnail'] ?? null,
                'published_at' => $articleData['webPublicationDate'],
                'metadata' => $articleData,
            ]);
        }
    }
}
