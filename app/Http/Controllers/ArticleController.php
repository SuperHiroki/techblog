<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Article;
use App\Models\Author;

use App\Helpers\OgImageHelper;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_admin) {
                return response('This is article edit page. Please login as admin.', 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        // 著者、いいね数、ブックマーク数、アーカイブ数を含めて記事を取得
        $articles = Article::with(['author', 'likeUsers', 'bookmarkUsers', 'archiveUsers'])->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        //記事のリンクを取得
        $validatedData = $request->validate(['link' => 'required|url']);
    
        //記事のリンクからその他の記事の情報をフェッチ
        try {
            $metaData = OgImageHelper::getMetaData($validatedData['link']);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        //ドメインチェックと同時にDBに記事を追加する。
        try {
            Article::createWithDomainCheck($validatedData['link'], $metaData);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    
        return redirect()->route('articles.index');
    } 

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }
}

