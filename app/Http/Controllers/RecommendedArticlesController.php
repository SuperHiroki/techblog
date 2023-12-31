<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RecommendedArticlesController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'likes');
        $period = $request->input('period', 'week');

        $articles = Article::query();

        switch ($sort) {
            case 'likes':
                $articles->withCount('likeUsers')->orderBy('like_users_count', 'desc');
                break;
            case 'bookmarks':
                $articles->withCount('bookmarkUsers')->orderBy('bookmark_users_count', 'desc');
                break;
            case 'archives':
                $articles->withCount('archiveUsers')->orderBy('archive_users_count', 'desc');
                break;
            case 'newest':
                $articles->orderBy('created_date', 'desc');
                break;
            // いいね数、ブックマーク数、アーカイブ数の急上昇ソート
            default:
                $articles->applyTrendingSort($sort, $period);
                break;
        }

        //$articles = $articles->get();
        $articles = $articles->paginate(5);

        // ユーザーが行ったアクションの状態を取得
        $user = Auth::user();
        $likeArticles = $user ? $user->likeArticles : collect([]);
        $bookmarkArticles = $user ? $user->bookmarkArticles : collect([]);
        $archiveArticles = $user ? $user->archiveArticles : collect([]);

        return view('recommended-articles', compact('articles', 'likeArticles', 'bookmarkArticles', 'archiveArticles'));
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

