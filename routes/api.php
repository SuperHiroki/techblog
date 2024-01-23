<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ArticleActionController;
use App\Http\Controllers\Api\ArticleAsyncActionController;
use App\Http\Controllers\Api\AuthorAsyncActionController;
use App\Http\Controllers\Api\CommentsAsyncActionController;
use App\Http\Controllers\Api\Auth\LoginController;

// トークンベースの認証が必要なルート
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/test', function (Request $request) {
        Log::info('RRRRRRRRRRRRRRRRRRRRRRRR This is api test route.');
        return response()->json(["message" => "you did it well."]);
    });

    //ユーザ情報の取得
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //クロム拡張機能からいいね（ブックマーク、アーカイブ）をつけることができる。bodyにURLを保持させる。
    Route::post('/like-article', [ArticleActionController::class, 'like']);
    Route::delete('/unlike-article', [ArticleActionController::class, 'unlike']);
    Route::post('/bookmark-article', [ArticleActionController::class, 'bookmark']);
    Route::delete('/unbookmark-article', [ArticleActionController::class, 'unbookmark']);
    Route::post('/archive-article', [ArticleActionController::class, 'archive']);
    Route::delete('/unarchive-article', [ArticleActionController::class, 'unarchive']);
    //クロム拡張機能からいいね（ブックマーク、アーカイブ）の状態の取得。
    Route::get('/get-state', [ArticleActionController::class, 'getState']);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //非同期処理でいいね（ブックマーク、アーカイブ）をつける。
    Route::post('/like-article/{article}', [ArticleAsyncActionController::class, 'likeArticleFromArticleId'])->name('api.articles.like');
    Route::delete('/unlike-article/{article}', [ArticleAsyncActionController::class, 'unlikeArticleFromArticleId'])->name('api.articles.unlike');
    Route::post('/bookmark-article/{article}', [ArticleAsyncActionController::class, 'bookmarkArticleFromArticleId'])->name('api.articles.bookmark');
    Route::delete('/unbookmark-article/{article}', [ArticleAsyncActionController::class, 'unbookmarkArticleFromArticleId'])->name('api.articles.unbookmark');
    Route::post('/archive-article/{article}', [ArticleAsyncActionController::class, 'archiveArticleFromArticleId'])->name('api.articles.archive');
    Route::delete('/unarchive-article/{article}', [ArticleAsyncActionController::class, 'unarchiveArticleFromArticleId'])->name('api.articles.unarchive');
    Route::post('/trash-article/{article}', [ArticleAsyncActionController::class, 'trashArticleFromArticleId'])->name('api.articles.trash');
    Route::delete('/untrash-article/{article}', [ArticleAsyncActionController::class, 'untrashArticleFromArticleId'])->name('api.articles.untrash');

    /////////////////////////////////////////////////////////////////////////////
    // フォローとフォロー解除のルート
    Route::post('/follow-author/{author}', [AuthorAsyncActionController::class, 'followAuthorFromAuthorId'])->name('api.authors.follow');
    Route::delete('/unfollow-author/{author}', [AuthorAsyncActionController::class, 'unfollowAuthorFromAuthorId'])->name('api.authors.unfollow');
    Route::post('/trash-author/{author}', [AuthorAsyncActionController::class, 'trashAuthorFromAuthorId'])->name('api.authors.trash');
    Route::delete('/untrash-author/{author}', [AuthorAsyncActionController::class, 'untrashAuthorFromAuthorId'])->name('api.authors.untrash');

    /////////////////////////////////////////////////////////////////////////////
    #コメント
    #返信一覧
    Route::get('/comments/{comment}/replies', [CommentsAsyncActionController::class, 'replies'])->name('api.comments.replies');
    #追加、削除、編集、報告
    Route::post('/comments', [CommentsAsyncActionController::class, 'add'])->name('api.comments.add');
    Route::patch('/comments/{comment}', [CommentsAsyncActionController::class, 'update'])->name('api.comments.update');
    Route::delete('/comments/{comment}', [CommentsAsyncActionController::class, 'destroy'])->name('api.comments.destroy');
    Route::post('/comments/{comment}/report', [CommentsAsyncActionController::class, 'reports'])->name('api.comments.report');
    #いいね
    Route::post('/comments/{comment}/like', [CommentsAsyncActionController::class, 'like'])->name('api.comments.like');
    Route::delete('/comments/{comment}/like', [CommentsAsyncActionController::class, 'unlike'])->name('api.comments.unlike');

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
