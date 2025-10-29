<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        Source::factory()->create([
            'name' => 'NewsAPI',
            'base_url' => 'https://newsapi.org/v2/',
        ]);

        Source::factory()->create([
            'name' => 'OpenNews',
            'base_url' => 'https://opennews.io/',
        ]);

        Source::factory()->create([
            'name' => 'NewsCred',
            'base_url' => 'https://newscred.com/',
        ]);
    }
}
