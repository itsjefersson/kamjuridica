<?php

use Illuminate\Support\Facades\Route;

// Sirve la SPA (Vue) compilada en public/index.html.
// Las rutas /api/* se registran en routes/api.php y se ignoran aquí.
Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

Route::get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '^(?!api).*$');
