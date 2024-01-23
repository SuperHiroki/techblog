<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;

use App\Http\Controllers\MyPage\ProfileController;
use App\Http\Controllers\MyPage\FollowedAuthorsController;
use App\Http\Controllers\MyPage\LikesController;
use App\Http\Controllers\MyPage\BookmarksController;
use App\Http\Controllers\MyPage\ArchivesController;
use App\Http\Controllers\MyPage\RecentArticlesController;

use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\PublicProfileController;

use App\Http\Controllers\RecommendedAuthorsController;
use App\Http\Controllers\RecommendedArticlesController;

use App\Http\Controllers\CommentsController;

use App\Http\Controllers\ApiTokenGetRedirectController;

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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('root');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#ログインなど
Auth::routes();

#adminページ
Route::resource('/authors', AuthorController::class);
Route::resource('/articles', ArticleController::class);

#おすすめ著者
Route::get('/recommended-authors', [App\Http\Controllers\RecommendedAuthorsController::class, 'index'])->name('recommended-authors');

#おすすめ記事
Route::get('/recommended-articles', [App\Http\Controllers\RecommendedArticlesController::class, 'index'])->name('recommended-articles');

#コメント
Route::get('/comments', [App\Http\Controllers\CommentsController::class, 'index'])->name('comments');

#ユーザ一覧
Route::get('/users', [App\Http\Controllers\UsersListController::class, 'index'])->name('users');

#マイページ
Route::prefix('my-page')->group(function () {
    //プロフィール
    Route::get('/{user}/profile', 'App\Http\Controllers\MyPage\ProfileController@index')->name('my-page.profile');
    //フォロー中の著者一覧
    Route::get('/{user}/followed-authors', 'App\Http\Controllers\MyPage\FollowedAuthorsController@index')->name('my-page.followed-authors');
    //trashした著者一覧
    Route::get('/{user}/trashed-authors', 'App\Http\Controllers\MyPage\TrashedAuthorsController@index')->name('my-page.trashed-authors');
    //最近の記事
    Route::get('/{user}/recent-articles/days/{days}', 'App\Http\Controllers\MyPage\RecentArticlesController@index')->name('my-page.recent-articles');
    //いいねした記事
    Route::get('/{user}/liked-articles', 'App\Http\Controllers\MyPage\LikesController@index')->name('my-page.liked-articles');
    //ブックマークした記事
    Route::get('/{user}/bookmarked-articles', 'App\Http\Controllers\MyPage\BookmarksController@index')->name('my-page.bookmarked-articles');
    //アーカイブした記事
    Route::get('/{user}/archived-articles', 'App\Http\Controllers\MyPage\ArchivesController@index')->name('my-page.archived-articles');
    //trashした記事
    Route::get('/{user}/trashed-articles', 'App\Http\Controllers\MyPage\TrashesController@index')->name('my-page.trashed-articles');
});

#設定
Route::prefix('settings')->middleware('auth')->group(function () {
    //アカウント設定
    Route::get('/{user}/account', 'App\Http\Controllers\Settings\AccountController@index')->name('settings.account');
    Route::patch('/{user}/account', 'App\Http\Controllers\Settings\AccountController@update')->name('settings.account.update');
    //公開プロフィール設定
    Route::get('/{user}/public-profile', 'App\Http\Controllers\Settings\PublicProfileController@index')->name('settings.public-profile');
    Route::patch('/{user}/public-profile', 'App\Http\Controllers\Settings\PublicProfileController@update')->name('settings.public-profile.update');
});

#APIトークンを取得するためのページ(すぐにリダイレクトされるから一瞬のみしか表示されない。)
Route::get('/api-token-get-redirect', [App\Http\Controllers\ApiTokenRedirectController::class, 'get'])->name('api-token-get-redirect');
Route::get('/api-token-throw-away-redirect', [App\Http\Controllers\ApiTokenRedirectController::class, 'throwAway'])->name('api-token-throw-away-redirect');
