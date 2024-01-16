<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ArticleActionController;
use App\Http\Controllers\Api\Auth\LoginController;

// トークンベースの認証が必要なルート
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/test', function (Request $request) {
        Log::info('RRRRRRRRRRRRRRRRRRRRRRRR This is api test route.');
        return response()->json(["message" => "you did it well."]);
    });


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //bodyにURLを保持させる。クロム拡張機能で使う。
    Route::post('/like-article', [ArticleActionController::class, 'like'])->name('api-like-article');
    Route::delete('/unlike-article', [ArticleActionController::class, 'unlike'])->name('api-unlike-article');
    Route::post('/bookmark-article', [ArticleActionController::class, 'bookmark'])->name('api-bookmark-article');
    Route::delete('/unbookmark-article', [ArticleActionController::class, 'unbookmark'])->name('api-unbookmark-article');
    Route::post('/archive-article', [ArticleActionController::class, 'archive'])->name('api-archive-article');
    Route::delete('/unarchive-article', [ArticleActionController::class, 'unarchive'])->name('api-unarchive-article');
    //非同期処理で使う。いいね（ブックマーク、アーカイブ）の状態の取得。
    Route::get('/get-state', [ArticleActionController::class, 'getState'])->name('api-get-state');

    //非同期処理のために。
    Route::post('/like-article/{article}', [ArticleActionController::class, 'likeArticleFromArticleId'])->name('api-like-article-from-article-id');
    Route::delete('/unlike-article/{article}', [ArticleActionController::class, 'unlikeArticleFromArticleId'])->name('api-unlike-article-from-article-id');
    Route::post('/bookmark-article/{article}', [ArticleActionController::class, 'bookmarkArticleFromArticleId'])->name('api-bookmark-article-from-article-id');
    Route::delete('/unbookmark-article/{article}', [ArticleActionController::class, 'unbookmarkArticleFromArticleId'])->name('api-unbookmark-article-from-article-id');
    Route::post('/archive-article/{article}', [ArticleActionController::class, 'archiveArticleFromArticleId'])->name('api-archive-article-from-article-id');
    Route::delete('/unarchive-article/{article}', [ArticleActionController::class, 'unarchiveArticleFromArticleId'])->name('api-unarchive-article-from-article-id');
});

// トークンベースの認証が不要なルート
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return response()->json([
            'apiToken' => Auth::user()->createToken('apiToken')->plainTextToken
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});
