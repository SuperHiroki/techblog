<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;

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
Route::get('/', function () {
    Log::info('CCCCCCCCCCCCCCCCCCCCCCCCCC Your debug message');
    return view('test');
});
Route::get('/test', function () {
    Log::info('AAAAAAAAAAAAAAAAAAAAAAAAA Your debug message');
    return view('test');
});
Route::get('/test2', function () {
    Log::info('FFFFFFFFFFFFFFFFFF Your debug message');
    return view('test2');
});
Route::get('/log-test', function () {
    Log::info('Log test from web route.');
    return 'Log test complete, check the logs!';
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#ログインなど
Auth::routes();

#adminページ
Route::resource('articles', ArticleController::class);
Route::resource('authors', AuthorController::class);

#おすすめ著者
Route::get('/recommended-authors', [App\Http\Controllers\RecommendedAuthorsController::class, 'index'])->name('recommended-authors');