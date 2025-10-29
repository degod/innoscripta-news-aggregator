<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch and store news articles from external APIs';

    public function handle(NewsAggregatorService $service)
    {
        $service->fetchFromNewsAPI();
        $this->info('News successfully fetched!');
    }
}
