<?php

namespace App\Services\News;

interface NewsProviderInterface
{
    /**
     * Return a human-readable provider name.
     */
    public function name(): string;

    /**
     * Fetch articles from the provider and return an iterable of normalized DTOs.
     * Implementations should handle provider-specific HTTP and mapping.
     *
     * @return iterable<ProviderArticleDTO>
     */
    public function fetch(): iterable;
}
