<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'base_url',
        'description',
        'logo_url',
        'country',
        'language'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($source) {
            if (empty($source->uuid)) {
                $source->uuid = Str::uuid()->toString();
            }
        });
    }
}
