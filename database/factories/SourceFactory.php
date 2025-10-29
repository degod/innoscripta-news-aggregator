<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SourceFactory extends Factory
{
    protected $model = Source::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'name' => $this->faker->unique()->company(),
            'base_url' => $this->faker->url(),
            'description' => $this->faker->sentence(),
            'logo_url' => $this->faker->imageUrl(),
            'country' => $this->faker->countryCode(),
            'language' => 'en',
        ];
    }
}
