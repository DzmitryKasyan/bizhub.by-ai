<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'meta_description', 'is_published'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
