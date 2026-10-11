<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    /**
     * Lista los artículos publicados, del más reciente al más antiguo.
     */
    public function index(): AnonymousResourceCollection
    {
        $articles = Article::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->get();

        return ArticleResource::collection($articles);
    }

    /**
     * Devuelve un artículo publicado por su slug.
     */
    public function show(string $slug): ArticleResource
    {
        $article = Article::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return new ArticleResource($article);
    }
}
