<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Article;

use App\Http\Controllers\Controller;

use App\Helpers\OgImageHelper;

class ArticleActionController extends Controller
{
    //記事がまだ登録されていない場合、記事を追加する。
    public function addArticle($articleUrl) {
        $decodedUrl = urldecode($articleUrl);
        $article = Article::where('link', $decodedUrl)->first();
        if (!$article) {
            try {
                $metaData = OgImageHelper::getMetaData($decodedUrl);
                $article = Article::createWithDomainCheck($decodedUrl, $metaData);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $article;
    }

    ////////////////////////////////////////////////////////////////////////////////
    //記事にいいねを付ける
    public function like(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->addArticle($articleUrl);
            if ($article->likeUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'SSSSSSSSS Already liked.'], 409);
            }
    
            $article->likeUsers()->attach(Auth::id());
            return response()->json(['message' => 'SSSSSSSSS Liked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "SSSSSSSSS Error in processing request: {$e->getMessage()}"], 500);
        }
    }

    //記事のいいねを外す。
    public function unlike(Request $request) {

        $articleUrl = $request->query('articleUrl');

        Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);

        $article = $this->addArticle($articleUrl);
        if (!$article->likeUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not liked'], 409);
        }
        $article->likeUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unliked successfully']);
    }
    
    //記事にブックマークを付ける
    public function bookmark(Request $request) {

        $articleUrl = $request->query('articleUrl');

        Log::info('UUUUUUUUUUUUUUUUUUUUU' . $articleUrl);

        $article = $this->addArticle($articleUrl);
        if ($article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already bookmarked'], 409);
        }
        $article->bookmarkUsers()->attach(Auth::id());
        return response()->json(['message' => 'Bookmarked successfully']);
    }
    
    //記事のブックマークを外す
    public function unbookmark(Request $request) {

        $articleUrl = $request->query('articleUrl');

        Log::info('UUUUUUUUUUUUUUUUUUUUU' . $articleUrl);

        $article = $this->addArticle($articleUrl);
        if (!$article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not bookmarked'], 409);
        }
        $article->bookmarkUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unbookmarked successfully']);
    }

    //記事にアーカイブをつける
    public function archive(Request $request) {

        $articleUrl = $request->query('articleUrl');

        Log::info('UUUUUUUUUUUUUUUUUUUUU' . $articleUrl);

        $article = $this->addArticle($articleUrl);
        if ($article->archiveUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already archived'], 409);
        }
    
        $article->archiveUsers()->attach(Auth::id());
        return response()->json(['message' => 'Archived successfully']);
    }
    
    //記事のアーカイブを外す
    public function unarchive(Request $request) {

        $articleUrl = $request->query('articleUrl');

        Log::info('UUUUUUUUUUUUUUUUUUUUU' . $articleUrl);

        $article = $this->addArticle($articleUrl);
        if (!$article->archiveUsers()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Not archived'], 409);
        }
    
        $article->archiveUsers()->detach(Auth::id());
        return response()->json(['message' => 'Unarchived successfully']);
    }
    
}
