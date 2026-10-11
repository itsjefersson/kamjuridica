<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialPostResource;
use App\Models\SocialPost;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialPostController extends Controller
{
    /**
     * Devuelve las publicaciones activas (visibles en el sitio público).
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = SocialPost::query()
            ->where('active', true)
            ->orderByDesc('created_at')
            ->get();

        return SocialPostResource::collection($posts);
    }
}
