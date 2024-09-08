<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/test', function () {
    return 'test';
});

Route::prefix('/v1')->group(function () {
    Route::prefix('/authors')->group(function () {
        Route::get('/', [App\Http\Controllers\AuthorController::class, 'listAuthors']);
        Route::get('/{id}', [App\Http\Controllers\AuthorController::class, 'getAuthorById']);
        Route::post('/', [App\Http\Controllers\AuthorController::class, "store"]);

        Route::put('/{id}', [App\Http\Controllers\AuthorController::class, "update"]);
        Route::delete('/{id}', [App\Http\Controllers\AuthorController::class, "delete"]);

        Route::get('/{id}/books', [App\Http\Controllers\AuthorController::class, 'getAllBookByAuthor']);

        Route::get('/{id}/books/elasticsearch', [App\Http\Controllers\AuthorController::class, 'getAllBookByAuthorElasticsearch']);
    });

    Route::prefix('/books')->group(function () {
        Route::get('/', [App\Http\Controllers\BookController::class, 'listBooks']);
        Route::get('/{id}', [App\Http\Controllers\BookController::class, 'getBookById']);
        Route::post('/', [App\Http\Controllers\BookController::class, "store"]);

        Route::put('/{id}', [App\Http\Controllers\BookController::class, "update"]);
        Route::delete('/{id}', [App\Http\Controllers\BookController::class, "delete"]);
    });
});
