<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ArticleActionController;
use App\Http\Controllers\Api\Auth\LoginController;

// トークンベースの認証が必要なルート
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/test', function (Request $request) {
        Log::info('RRRRRRRRRRRRRRRRRRRRRRRR');
        return response()->json(["message" => "you did it well."]);
    });


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/like-article/url/{articleUrl}', [ArticleActionController::class, 'like'])->name('api-like-article');
    Route::delete('/unlike-article/url/{articleUrl}', [ArticleActionController::class, 'unlike'])->name('api-unlike-article');
    Route::post('/bookmark-article/url/{articleUrl}', [ArticleActionController::class, 'bookmark'])->name('api-bookmark-article');
    Route::delete('/unbookmark-article/url/{articleUrl}', [ArticleActionController::class, 'unbookmark'])->name('api-unbookmark-article');
    Route::post('/archive-article/url/{articleUrl}', [ArticleActionController::class, 'archive'])->name('api-archive-article');
    Route::delete('/unarchive-article/url/{articleUrl}', [ArticleActionController::class, 'unarchive'])->name('api-unarchive-article');
});

// トークンベースの認証が不要なルート
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return response()->json([
            'token' => Auth::user()->createToken('api-token')->plainTextToken
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});
