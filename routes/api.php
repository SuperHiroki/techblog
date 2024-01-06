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

    Route::post('/like-article', [ArticleActionController::class, 'like'])->name('api-like-article');
    Route::delete('/unlike-article', [ArticleActionController::class, 'unlike'])->name('api-unlike-article');
    Route::post('/bookmark-article', [ArticleActionController::class, 'bookmark'])->name('api-bookmark-article');
    Route::delete('/unbookmark-article', [ArticleActionController::class, 'unbookmark'])->name('api-unbookmark-article');
    Route::post('/archive-article', [ArticleActionController::class, 'archive'])->name('api-archive-article');
    Route::delete('/unarchive-article', [ArticleActionController::class, 'unarchive'])->name('api-unarchive-article');

    Route::get('/get-state', [ArticleActionController::class, 'getState'])->name('api-get-state');
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
