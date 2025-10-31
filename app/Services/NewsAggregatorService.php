<?php

namespace App\Services;

use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\News\NewsProviderInterface;

class NewsAggregatorService
{
    /** @var NewsProviderInterface[] */
    private array $providers;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        NewsProviderInterface ...$providers,
    ) {
        $this->providers = $providers;
    }

    public function orchestrate(): void
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->fetch() as $dto) {
                // Skip if URL already exists
                if ($this->articleRepository->existsByUrl($dto->url)) {
                    continue;
                }
                $this->articleRepository->create($dto->toArticlePayload());
            }
        }
    }
}
