<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('portal');
});

Route::get('/portal', function () {
    return view('portal');
});

Route::get('/api/docs', function () {
    return view('swagger');
})->name('api.docs');

Route::get('/docs/openapi.json', function () {
    $path = public_path('docs/openapi.json');
    if (file_exists($path)) {
        return response()->file($path, ['Content-Type' => 'application/json']);
    }
    abort(404);
});

Route::get('/docs', function () {
    return redirect('/api/docs');
});

Route::get('/api-docs', function () {
    return redirect('/api/docs');
});
