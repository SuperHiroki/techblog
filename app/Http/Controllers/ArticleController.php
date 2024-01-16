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

    //記事一覧のページを表示する。
    public function index()
    {
        // 著者、いいね数、ブックマーク数、アーカイブ数を含めて記事を取得
        $articles = Article::with(['author', 'likeUsers', 'bookmarkUsers', 'archiveUsers'])->get();
        return view('articles.index', compact('articles'));
    }

    //記事作成フォームのページを提供する。
    public function create()
    {
        return view('articles.create');
    }

    //記事を作成
    public function store(Request $request)
    {
        $validatedData = $request->validate(['link' => 'required|url']);
    
        try {
            $existingArticle = Article::where('link', $validatedData['link'])->first();
            if ($existingArticle) {
                throw new \Exception('The article already exists.');
            }
            $metaData = OgImageHelper::getMetaData($validatedData['link']);
            Article::createWithHasAuthorCheck($validatedData['link'], $metaData);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('articles.index')->with('success', "記事が作成されました。");
    }
    
    //記事の削除
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', "記事が削除されました。");
    }

    //記事の更新
    public function update(Article $article)
    {
        $articlelink = $article->link;

        try {
            $existingArticle = Article::where('link', $articlelink)->first();
            if (!$existingArticle) {
                throw new \Exception('The article does not exists.');
            }
            $metaData = OgImageHelper::getMetaData($articlelink);
            Article::updateArticle($articlelink, $metaData);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('articles.index')->with('success', "記事が更新されました。");
    }
}

