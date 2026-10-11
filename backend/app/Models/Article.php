<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['title', 'excerpt', 'content', 'featured_image', 'youtube_url', 'status', 'published_at'])]
class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Genera un slug único a partir del título antes de guardar.
     */
    protected static function booted(): void
    {
        static::saving(function (Article $article) {
            if (empty($article->slug) || $article->isDirty('title')) {
                $article->slug = $article->generateUniqueSlug();
            }
        });
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug($this->title);
        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
