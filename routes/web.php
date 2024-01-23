<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\ArticlesController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecommendedAuthorsController;
use App\Http\Controllers\RecommendedArticlesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\UsersController;

use App\Http\Controllers\MyPage\ProfileController;
use App\Http\Controllers\MyPage\FollowedAuthorsController;
use App\Http\Controllers\MyPage\TrashedAuthorsController;
use App\Http\Controllers\MyPage\RecentArticlesController;
use App\Http\Controllers\MyPage\LikedArticlesController;
use App\Http\Controllers\MyPage\BookmarkedArticlesController;
use App\Http\Controllers\MyPage\ArchivedArticlesController;
use App\Http\Controllers\MyPage\TrashedArticlesController;

use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\PublicProfileController;

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

#ログインなど
Auth::routes();

#adminページ
Route::resource('/authors', AuthorsController::class);
Route::resource('/articles', ArticlesController::class);

#ホーム
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/home', [HomeController::class, 'index'])->name('home');

#おすすめ著者
Route::get('/recommended-authors', [RecommendedAuthorsController::class, 'index'])->name('recommended-authors');

#おすすめ記事
Route::get('/recommended-articles', [RecommendedArticlesController::class, 'index'])->name('recommended-articles');

#コメント
Route::get('/comments', [CommentsController::class, 'index'])->name('comments');

#ユーザ一覧
Route::get('/users', [UsersController::class, 'index'])->name('users');

#マイページ
Route::prefix('my-page')->group(function () {
    //プロフィール
    Route::get('/{user}/profile', [ProfileController::class, 'index'])->name('my-page.profile');
    //フォロー中の著者一覧
    Route::get('/{user}/followed-authors', [FollowedAuthorsController::class, 'index'])->name('my-page.followed-authors');
    //trashした著者一覧
    Route::get('/{user}/trashed-authors', [TrashedAuthorsController::class, 'index'])->name('my-page.trashed-authors');
    //最近の記事
    Route::get('/{user}/recent-articles/days/{days}', [RecentArticlesController::class, 'index'])->name('my-page.recent-articles');
    //いいねした記事
    Route::get('/{user}/liked-articles', [LikedArticlesController::class, 'index'])->name('my-page.liked-articles');
    //ブックマークした記事
    Route::get('/{user}/bookmarked-articles', [BookmarkedArticlesController::class, 'index'])->name('my-page.bookmarked-articles');
    //アーカイブした記事
    Route::get('/{user}/archived-articles', [ArchivedArticlesController::class, 'index'])->name('my-page.archived-articles');
    //trashした記事
    Route::get('/{user}/trashed-articles', [TrashedArticlesController::class, 'index'])->name('my-page.trashed-articles');
});

#設定
Route::prefix('settings')->middleware('auth')->group(function () {
    //アカウント設定
    Route::get('/{user}/account', [AccountController::class, 'index'])->name('settings.account');
    Route::patch('/{user}/account', [AccountController::class, 'update'])->name('settings.account.update');
    //公開プロフィール設定
    Route::get('/{user}/public-profile', [PublicProfileController::class, 'index'])->name('settings.public-profile');
    Route::patch('/{user}/public-profile', [PublicProfileController::class, 'update'])->name('settings.public-profile.update');
});


#APIトークンを取得するためのページ(すぐにリダイレクトされるから一瞬のみしか表示されない。)
Route::get('/api-token-get-redirect', [ApiTokenRedirectController::class, 'get'])->name('api-token-get-redirect');
Route::get('/api-token-throw-away-redirect', [ApiTokenRedirectController::class, 'throwAway'])->name('api-token-throw-away-redirect');
