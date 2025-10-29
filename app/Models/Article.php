<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'source',
        'author',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->uuid)) {
                $article->uuid = Str::uuid()->toString();
            }
        });
    }
}
