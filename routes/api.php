<?php

use App\Http\Controllers\Article\DistinctAuthorsController;
use App\Http\Controllers\Article\DistinctCategoriesController;
use App\Http\Controllers\Article\DistinctSourcesController;
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

    Route::prefix('articles')->group(function () {
        Route::get('/', ListArticlesController::class)->name('articles.index');
        Route::post('/preferences', FilterArticlesByPreferencesController::class)->name('articles.preferences');
        Route::get('/sources', DistinctSourcesController::class)->name('articles.sources');
        Route::get('/categories', DistinctCategoriesController::class)->name('articles.categories');
        Route::get('/authors', DistinctAuthorsController::class)->name('articles.authors');
    });
});
