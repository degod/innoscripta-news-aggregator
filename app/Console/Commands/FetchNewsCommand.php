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
        $this->info('Fetching news...');
        $service->fetchFromNewsAPI();
        $service->fetchFromNewYorkTimes();
        $service->fetchFromTheGuardian();
        $this->info('News successfully fetched!');
    }
}
