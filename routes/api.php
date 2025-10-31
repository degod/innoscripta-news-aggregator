<?php

use App\Http\Controllers\Article\DistinctAttributesController;
use App\Http\Controllers\Article\FilterArticlesByPreferencesController;
use App\Http\Controllers\Article\ListArticlesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::prefix('v1/')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', RegisterController::class)->name('auth.register');
        Route::post('/login', LoginController::class)->name('auth.login');
    });

    Route::middleware('jwt.auth')->group(function () {
        Route::prefix('articles')->group(function () {
            Route::get('/', ListArticlesController::class)->name('articles.index');
            Route::post('/preferences', FilterArticlesByPreferencesController::class)->name('articles.preferences');
            Route::get('/{attribute}', DistinctAttributesController::class)
                ->whereIn('attribute', ['sources','categories','authors'])
                ->name('articles.distinct');
        });
    });
});
