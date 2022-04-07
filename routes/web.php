<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->group(__DIR__ . '/route.php');

// Add this route last as a catch-all for undefined routes.
Route::get(
    '/{path?}',
    function (Request $request) {
        // ...
        // If the request expects JSON, it means that
        // someone sent a request to an invalid route.
        if ($request->expectsJson()) {
            abort(404);
        }

        // Fetch and display the page from the render path on nuxt dev server or fallback to static file
        return file_get_contents(env('NUXT_OUTPUT_PATH', public_path('spa.html')));
    }
)->where('path', '^(?!api).*$')
    // Redirect to Nuxt from within Laravel
    // by using Laravels route helper
    // e.g.: `route('nuxt', ['path' => '/<nuxtPath>'])`
    ->name('nuxt');
