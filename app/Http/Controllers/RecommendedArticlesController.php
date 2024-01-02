<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use App\Models\Article;
use App\Models\User;

use App\Helpers\ParameterValidationHelper;

class RecommendedArticlesController extends Controller
{
    public function index(Request $request)
    {
        //パラメタがない場合、デフォルトのパラメタにリダイレクト
        if (!$request->has('sort')) {
            return redirect()->route(Route::currentRouteName(), ['sort' => 'likes']);
        }

        try {
            //バリデーションチェック
            ParameterValidationHelper::validateParametersSortArticles($request);
            //ソート
            $articles = Article::sortBy($request->input('sort'),  $request->input('period'))->paginate(5);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    
        return view('recommended-articles', compact('articles'));
    }    

    //いいね、ブックマーク、アーカイブをつける処理
    public function like(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->likeArticles()->attach($article->id);
        return back();
    }

    public function unlike(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->likeArticles()->detach($article->id);
        return back();
    }

    public function bookmark(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->bookmarkArticles()->attach($article->id);
        return back();
    }

    public function unbookmark(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->bookmarkArticles()->detach($article->id);
        return back();
    }

    public function archive(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->archiveArticles()->attach($article->id);
        return back();
    }

    public function unarchive(Article $article)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        Auth::user()->archiveArticles()->detach($article->id);
        return back();
    }
}

