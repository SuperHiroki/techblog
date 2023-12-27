<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendedArticlesController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'likes');
        $period = $request->input('period', 'week');

        $articles = Article::query();

        switch ($sort) {
            case 'likes':
                $articles->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            case 'bookmarks':
                $articles->withCount('bookmarks')->orderBy('bookmarks_count', 'desc');
                break;
            case 'archives':
                $articles->withCount('archives')->orderBy('archives_count', 'desc');
                break;
            case 'newest':
                $articles->orderBy('created_at', 'desc');
                break;
            // いいね数、ブックマーク数、アーカイブ数の急上昇ソート
            case 'trending_likes':
            case 'trending_bookmarks':
            case 'trending_archives':
                $relation = str_replace('trending_', '', $sort);
                $articles->withCount([$relation => function ($query) use ($period) {
                    if ($period === 'week') $query->where('created_at', '>=', now()->subWeek());
                    elseif ($period === 'month') $query->where('created_at', '>=', now()->subMonth());
                    elseif ($period === 'year') $query->where('created_at', '>=', now()->subYear());
                }])->orderBy($relation . '_count', 'desc');
                break;
        }

        $articles = $articles->get();

        // ユーザーが行ったアクションの状態を取得
        $goodArticles = Auth::user()->goodArticles;
        $bookmarkArticles = Auth::user()->bookmarkArticles;
        $archiveArticles = Auth::user()->archiveArticles;

        return view('recommended-articles', compact('articles', 'goodArticles', 'bookmarkArticles', 'archiveArticles'));
    }




    public function like(Article $article)
    {
        Auth::user()->likes()->attach($article->id);
        return back();
    }

    public function unlike(Article $article)
    {
        Auth::user()->likes()->detach($article->id);
        return back();
    }

    public function bookmark(Article $article)
    {
        Auth::user()->bookmarks()->attach($article->id);
        return back();
    }

    public function unbookmark(Article $article)
    {
        Auth::user()->bookmarks()->detach($article->id);
        return back();
    }

    public function archive(Article $article)
    {
        Auth::user()->archives()->attach($article->id);
        return back();
    }

    public function unarchive(Article $article)
    {
        Auth::user()->archives()->detach($article->id);
        return back();
    }
}
