<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SocialPostController as AdminSocialPostController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SocialPostController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Rutas públicas y administrativas de la API.
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'time' => now()->toIso8601String(),
    ]);
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/social-posts', [SocialPostController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Rutas administrativas (protegidas con Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me', [AuthController::class, 'me']);

    Route::post('/admin/upload', [ImageController::class, 'store']);

    Route::apiResource('/admin/articles', AdminArticleController::class);
    Route::apiResource('/admin/social-posts', AdminSocialPostController::class);

    Route::get('/admin/settings', [AdminSettingController::class, 'index']);
    Route::put('/admin/settings', [AdminSettingController::class, 'update']);
});
