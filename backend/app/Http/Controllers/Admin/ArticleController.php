<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    /**
     * Lista todos los artículos (borradores y publicados).
     */
    public function index(): AnonymousResourceCollection
    {
        $articles = Article::query()->orderByDesc('created_at')->get();

        return ArticleResource::collection($articles);
    }

    /**
     * Crea un artículo nuevo.
     */
    public function store(StoreArticleRequest $request): ArticleResource
    {
        $data = $request->validated();

        // Al publicar por primera vez, se asigna la fecha de publicación.
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        return new ArticleResource($article);
    }

    /**
     * Muestra un artículo específico (para edición).
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    /**
     * Actualiza un artículo existente.
     */
    public function update(UpdateArticleRequest $request, Article $article): ArticleResource
    {
        $data = $request->validated();

        // Asigna fecha de publicación al pasar a publicado, si no la tiene.
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return new ArticleResource($article);
    }

    /**
     * Elimina un artículo.
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(['message' => 'Artículo eliminado.']);
    }
}
