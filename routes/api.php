<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//クロム拡張機能
use App\Http\Controllers\Api\ArticlesChromeExtensionController;
//通常の非同期処理
use App\Http\Controllers\Api\ArticlesAsyncActionController;
use App\Http\Controllers\Api\AuthorsAsyncActionController;
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
    //クロム拡張機能からいいね（ブックマーク、アーカイブ）をつけることができる。クエリパラメータにURLを保持させる。
    Route::post('/like-article', [ArticlesChromeExtensionController::class, 'like']);
    Route::delete('/unlike-article', [ArticlesChromeExtensionController::class, 'unlike']);
    Route::post('/bookmark-article', [ArticlesChromeExtensionController::class, 'bookmark']);
    Route::delete('/unbookmark-article', [ArticlesChromeExtensionController::class, 'unbookmark']);
    Route::post('/archive-article', [ArticlesChromeExtensionController::class, 'archive']);
    Route::delete('/unarchive-article', [ArticlesChromeExtensionController::class, 'unarchive']);
    Route::post('/trash-article', [ArticlesChromeExtensionController::class, 'trash']);
    Route::delete('/untrash-article', [ArticlesChromeExtensionController::class, 'untrash']);
    //クロム拡張機能からいいね（ブックマーク、アーカイブ）の状態の取得。
    Route::get('/get-state', [ArticlesChromeExtensionController::class, 'getState']);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //非同期処理でいいね（ブックマーク、アーカイブ）をつける。
    Route::post('/like-article/{article}', [ArticlesAsyncActionController::class, 'likeArticleFromArticleId'])->name('api.articles.like');
    Route::delete('/unlike-article/{article}', [ArticlesAsyncActionController::class, 'unlikeArticleFromArticleId'])->name('api.articles.unlike');
    Route::post('/bookmark-article/{article}', [ArticlesAsyncActionController::class, 'bookmarkArticleFromArticleId'])->name('api.articles.bookmark');
    Route::delete('/unbookmark-article/{article}', [ArticlesAsyncActionController::class, 'unbookmarkArticleFromArticleId'])->name('api.articles.unbookmark');
    Route::post('/archive-article/{article}', [ArticlesAsyncActionController::class, 'archiveArticleFromArticleId'])->name('api.articles.archive');
    Route::delete('/unarchive-article/{article}', [ArticlesAsyncActionController::class, 'unarchiveArticleFromArticleId'])->name('api.articles.unarchive');
    Route::post('/trash-article/{article}', [ArticlesAsyncActionController::class, 'trashArticleFromArticleId'])->name('api.articles.trash');
    Route::delete('/untrash-article/{article}', [ArticlesAsyncActionController::class, 'untrashArticleFromArticleId'])->name('api.articles.untrash');

    /////////////////////////////////////////////////////////////////////////////
    // フォローとフォロー解除のルート
    Route::post('/follow-author/{author}', [AuthorsAsyncActionController::class, 'followAuthorFromAuthorId'])->name('api.authors.follow');
    Route::delete('/unfollow-author/{author}', [AuthorsAsyncActionController::class, 'unfollowAuthorFromAuthorId'])->name('api.authors.unfollow');
    Route::post('/trash-author/{author}', [AuthorsAsyncActionController::class, 'trashAuthorFromAuthorId'])->name('api.authors.trash');
    Route::delete('/untrash-author/{author}', [AuthorsAsyncActionController::class, 'untrashAuthorFromAuthorId'])->name('api.authors.untrash');

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
