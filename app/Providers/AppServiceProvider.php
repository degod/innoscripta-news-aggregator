<?php

namespace App\Providers;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\News\GuardianProvider;
use App\Services\News\NewsApiProvider;
use App\Services\News\NewYorkTimesProvider;
use App\Services\NewsAggregatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        // Bind array of news providers
        $this->app->singleton('news.providers', function ($app) {
            return [
                $app->make(NewsApiProvider::class),
                $app->make(NewYorkTimesProvider::class),
                $app->make(GuardianProvider::class),
            ];
        });

        // Bind NewsAggregatorService with providers injected
        $this->app->singleton(NewsAggregatorService::class, function ($app) {
            return new NewsAggregatorService(
                $app->make(ArticleRepositoryInterface::class),
                ...$app->make('news.providers')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
