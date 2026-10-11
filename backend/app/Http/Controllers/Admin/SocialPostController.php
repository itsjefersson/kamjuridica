<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialPostRequest;
use App\Http\Resources\SocialPostResource;
use App\Models\SocialPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialPostController extends Controller
{
    /**
     * Lista todas las publicaciones (activas e inactivas).
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = SocialPost::query()->orderByDesc('created_at')->get();

        return SocialPostResource::collection($posts);
    }

    /**
     * Crea una publicación nueva.
     */
    public function store(SocialPostRequest $request): SocialPostResource
    {
        return new SocialPostResource(SocialPost::create($request->validated()));
    }

    /**
     * Actualiza una publicación existente.
     */
    public function update(SocialPostRequest $request, SocialPost $socialPost): SocialPostResource
    {
        $socialPost->update($request->validated());

        return new SocialPostResource($socialPost);
    }

    /**
     * Elimina una publicación.
     */
    public function destroy(SocialPost $socialPost): JsonResponse
    {
        $socialPost->delete();

        return response()->json(['message' => 'Publicación eliminada.']);
    }
}
