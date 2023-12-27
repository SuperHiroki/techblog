<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecommendedAuthorsController;
use App\Http\Controllers\RecommendedArticlesController;

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
Route::resource('articles', ArticleController::class);
Route::resource('authors', AuthorController::class);

#おすすめ著者
Route::get('/recommended-authors', [App\Http\Controllers\RecommendedAuthorsController::class, 'index'])->name('recommended-authors');
// フォローとフォロー解除のルート
Route::post('/follow-author/{author}', [RecommendedAuthorsController::class, 'followAuthor'])->name('follow-author');
Route::delete('/unfollow-author/{author}', [RecommendedAuthorsController::class, 'unfollowAuthor'])->name('unfollow-author');

#おすすめ記事
Route::get('/recommended-articles', [App\Http\Controllers\RecommendedArticlesController::class, 'index'])->name('recommended-articles');
//いいね
Route::post('/like-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'like'])->name('like-article');
Route::delete('/unlike-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'unlike'])->name('unlike-article');
//ブックマーク
Route::post('/bookmark-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'bookmark'])->name('bookmark-article');
Route::delete('/unbookmark-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'unbookmark'])->name('unbookmark-article');
//アーカイブ
Route::post('/archive-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'archive'])->name('archive-article');
Route::delete('/unarchive-article/{article}', [App\Http\Controllers\ArticleActionController::class, 'unarchive'])->name('unarchive-article');

#マイページ
Route::prefix('my-page')->middleware('auth')->group(function () {
    Route::get('/public-profile', 'App\Http\Controllers\MyPageController@publicProfile')->name('my-page.public-profile');
    Route::get('/followed-authors', 'App\Http\Controllers\MyPageController@followedAuthors')->name('my-page.followed-authors');
    Route::get('/recent-articles', 'App\Http\Controllers\MyPageController@recentArticles')->name('my-page.recent-articles');
    Route::get('/likes', 'App\Http\Controllers\MyPageController@likes')->name('my-page.likes');
    Route::get('/bookmarks', 'App\Http\Controllers\MyPageController@bookmarks')->name('my-page.bookmarks');
    Route::get('/archive', 'App\Http\Controllers\MyPageController@archive')->name('my-page.archive');
});

#設定
Route::prefix('settings')->middleware('auth')->group(function () {
    Route::get('/account', 'App\Http\Controllers\SettingsController@account')->name('settings.account');
    Route::get('/public-profile', 'App\Http\Controllers\SettingsController@publicProfile')->name('settings.public-profile');;
});
