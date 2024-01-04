<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class ArticleActionController extends Controller
{
    public function __construct()
    {
        // APIの認証を要求する
        $this->middleware('auth:api');
    }

    //記事がまだ登録されていない場合、記事を追加する。
    public function addArticle($articleUrl) {
        $decodedUrl = urldecode($articleUrl);
        $article = Article::where('link', $decodedUrl)->first();
        if (!$article) {
            try {
                $metaData = OgImageHelper::getMetaData($decodedUrl);
                $article = Article::createWithDomainCheck($decodedUrl, $metaData);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return $article;
    }

    ////////////////////////////////////////////////////////////////////////////////
    //記事にいいねを追加。
    public function like($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if ($article->likeUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already liked'], 409);
        }

        $article->likeUsers()->attach(Auth::id());
        return response()->json(['message' => 'Liked successfully']);
    }

    public function unlike($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if (!$article->likeUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not liked'], 409);
        }
        $article->likeUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unliked successfully']);
    }
    
    public function bookmark($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if ($article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already bookmarked'], 409);
        }
        $article->bookmarkUsers()->attach(Auth::id());
        return response()->json(['message' => 'Bookmarked successfully']);
    }
    
    public function unbookmark($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if (!$article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not bookmarked'], 409);
        }
        $article->bookmarkUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unbookmarked successfully']);
    }

    public function archive($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if ($article->archiveUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already archived'], 409);
        }
    
        $article->archiveUsers()->attach(Auth::id());
        return response()->json(['message' => 'Archived successfully']);
    }
    
    public function unarchive($articleUrl) {
        $article = $this->addArticle($articleUrl);
        if (!$article->archiveUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not archived'], 409);
        }
    
        $article->archiveUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unarchived successfully']);
    }
    
}
