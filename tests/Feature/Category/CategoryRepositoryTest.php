<?php

namespace Tests\Feature\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_category()
    {
        $repo = $this->app->make(CategoryRepositoryInterface::class);

        $data = [
            'name' => 'Technology',
            'slug' => 'technology',
            'description' => 'News related to innovations and gadgets',
        ];

        $category = $repo->create($data);

        $this->assertDatabaseHas('categories', ['name' => 'Technology']);
        $this->assertNotEmpty($category->uuid);
    }

    public function test_it_fetches_a_category_by_slug()
    {
        $repo = $this->app->make(CategoryRepositoryInterface::class);

        $category = $repo->create([
            'name' => 'Business',
            'slug' => 'business',
        ]);

        $found = $repo->findBySlug('business');

        $this->assertEquals($category->uuid, $found->uuid);
    }

    public function test_it_returns_all_categories()
    {
        $repo = $this->app->make(CategoryRepositoryInterface::class);

        $repo->create(['name' => 'Tech', 'slug' => 'tech']);
        $repo->create(['name' => 'Health', 'slug' => 'health']);

        $categories = $repo->all();

        $this->assertCount(2, $categories);
    }
}
