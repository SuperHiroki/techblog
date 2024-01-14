<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Author;
use App\Models\UserAuthorFollow;

use App\Helpers\ParameterValidationHelper;

class RecommendedAuthorsController extends Controller
{
    //著者一覧を表示する。
    public function index(Request $request)
    {
        //パラメタがない場合、デフォルトのパラメタにリダイレクト
        if (!$request->has('sort')) {
            return redirect()->route(Route::currentRouteName(), ['sort' => 'followers']);
        }

        try {
            //バリデーションチェック
            ParameterValidationHelper::validateParametersSortAuthors($request);

            //ソート
            $authors = Author::getSortedAuthors($request->input('sort'), $request->input('period', null))->paginate(20);
            //$authors = Author::getSortedAuthors($request->input('sort'), $request->input('period', null))->get();
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return view('recommended-authors', compact('authors'));
    }

    //フォロー
    public function followAuthor(Author $author)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $user->followedAuthors()->attach($author);
        return back()->with('success', "{$author->name}をフォローしました。");
    }

    //フォロー解除
    public function unfollowAuthor(Author $author)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $user->followedAuthors()->detach($author);
        return back()->with('success', "{$author->name}のフォローを外しました。");
    }
}


