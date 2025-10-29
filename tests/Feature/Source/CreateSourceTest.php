<?php

namespace Tests\Feature\Source;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Source\SourceRepositoryInterface;

class CreateSourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_source_via_repository()
    {
        $repo = $this->app->make(SourceRepositoryInterface::class);

        $data = [
            'name' => 'NewsAPI',
            'base_url' => 'https://newsapi.org/v2/',
            'description' => 'Global news aggregator',
            'logo_url' => 'https://logo.example/newsapi.png',
            'country' => 'us',
            'language' => 'en'
        ];

        $source = $repo->create($data);

        $this->assertDatabaseHas('sources', ['name' => 'NewsAPI']);
        $this->assertNotNull($source->uuid);
    }

    public function test_it_fetches_sources()
    {
        $repo = $this->app->make(SourceRepositoryInterface::class);

        $repo->create(['name' => 'OpenNews', 'base_url' => 'https://opennews.io']);
        $repo->create(['name' => 'NewsCred', 'base_url' => 'https://newscred.com']);

        $sources = $repo->all();

        $this->assertCount(2, $sources);
        $this->assertEquals('OpenNews', $sources[0]->name);
    }
}
