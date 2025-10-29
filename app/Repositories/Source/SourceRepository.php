<?php

namespace App\Repositories\Source;

use App\Models\Source;

class SourceRepository implements SourceRepositoryInterface
{
    public function __construct(private Source $source) {}

    public function create(array $data): Source
    {
        return $this->source->create($data);
    }

    public function all()
    {
        return $this->source->all();
    }

    public function findByUuid(string $uuid): ?Source
    {
        return $this->source->where('uuid', $uuid)->first();
    }

    public function findByName(string $name): ?Source
    {
        return $this->source->where('name', $name)->first();
    }
}
