<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecommendedAuthorsController;
use App\Http\Controllers\RecommendedArticlesController;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

#テストページ
Route::get('/test', function () {
    Log::info('AAAAAAAAAAAAAAAAAAAAAAAAA Your debug message');
    return view('test');
});
Route::get('/test2', function () {
    return view('test2');
});
Route::get('/log-test', function () {
    return 'Log test complete, check the logs!';
});

#ホーム
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#ログインなど
Auth::routes();

#adminページ
Route::resource('/authors', AuthorController::class);
Route::resource('/articles', ArticleController::class);

#おすすめ著者
Route::get('/recommended-authors', [App\Http\Controllers\RecommendedAuthorsController::class, 'index'])->name('recommended-authors');
// フォローとフォロー解除のルート
Route::post('/follow-author/{author}', [RecommendedAuthorsController::class, 'followAuthor'])->name('follow-author');
Route::delete('/unfollow-author/{author}', [RecommendedAuthorsController::class, 'unfollowAuthor'])->name('unfollow-author');

#おすすめ記事
Route::get('/recommended-articles', [App\Http\Controllers\RecommendedArticlesController::class, 'index'])->name('recommended-articles');
//いいね
Route::post('/like-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'like'])->name('like-article');
Route::delete('/unlike-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'unlike'])->name('unlike-article');
//ブックマーク
Route::post('/bookmark-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'bookmark'])->name('bookmark-article');
Route::delete('/unbookmark-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'unbookmark'])->name('unbookmark-article');
//アーカイブ
Route::post('/archive-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'archive'])->name('archive-article');
Route::delete('/unarchive-article/{article}', [App\Http\Controllers\RecommendedArticlesController::class, 'unarchive'])->name('unarchive-article');

#コメント
Route::get('/comments', [App\Http\Controllers\CommentsController::class, 'index'])->name('comments');
#追加、削除、編集
Route::post('/comments/add', [App\Http\Controllers\CommentsController::class, 'add'])->name('comments.add');
Route::patch('/comments/{comment}', [App\Http\Controllers\CommentsController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [App\Http\Controllers\CommentsController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/report/{comment}', [App\Http\Controllers\CommentsController::class, 'report'])->name('comments.report');

#マイページ
Route::prefix('my-page')->middleware('auth')->group(function () {
    Route::get('/public-profile', 'App\Http\Controllers\MyPageController@publicProfile')->name('my-page.public-profile');
    Route::get('/followed-authors', 'App\Http\Controllers\MyPageController@followedAuthors')->name('my-page.followed-authors');
    Route::get('/recent-articles', 'App\Http\Controllers\MyPageController@recentArticles')->name('my-page.recent-articles');
    Route::get('/likes', 'App\Http\Controllers\MyPageController@likes')->name('my-page.likes');
    Route::get('/bookmarks', 'App\Http\Controllers\MyPageController@bookmarks')->name('my-page.bookmarks');
    Route::get('/archives', 'App\Http\Controllers\MyPageController@archives')->name('my-page.archives');
});

#設定
Route::prefix('settings')->middleware('auth')->group(function () {
    Route::get('/account', 'App\Http\Controllers\SettingsController@account')->name('settings.account');
    Route::get('/public-profile', 'App\Http\Controllers\SettingsController@publicProfile')->name('settings.public-profile');;
});
