<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Article;
use App\Models\Author;

use App\Http\Controllers\Controller;

use App\Helpers\OgImageHelper;

class ArticlesChromeExtensionController extends Controller
{
    //記事がまだ登録されていない場合、記事を追加する。
    public function getOrAddArticleFromUrl($articleUrl) {
        $article = Article::where('link', $articleUrl)->first();
        if (!$article) {
            try {
                $metaData = OgImageHelper::getMetaData($articleUrl);
                $article = Article::createWithHasAuthorCheck($articleUrl, $metaData);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $article;
    }
    ////////////////////////////////////////////////////////////////////////////////
    //現在の状況を取得する。
    public function getState(Request $request) {
        $result = ['like' => false, 'bookmark' => false, 'archive' => false, 'trash' => false, 'followAuthor' => false, 'trashAuthor' => false];
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);

            //いいねなどをつけているかどうかを取得
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if ($article->likeUsers()->where('user_id', Auth::id())->exists()) {
                $result['like'] = true;
            }
            if ($article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
                $result['bookmark'] = true;
            }
            if ($article->archiveUsers()->where('user_id', Auth::id())->exists()) {
                $result['archive'] = true;
            }
            if ($article->trashUsers()->where('user_id', Auth::id())->exists()) {
                $result['trash'] = true;
            }

            //フォローしているかどうかも取得
            $author = $article->author; // まずは著者のインスタンスを取得
            if ($author->followers()->where('user_id', Auth::id())->exists()) {
                $result['followAuthor'] = true;
            }
            if ($author->trashedUsers()->where('user_id', Auth::id())->exists()) {
                $result['trashAuthor'] = true;
            }

            return response()->json(array_merge(['message' => 'Fetched user state successfully.'], $result));
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////
    //記事にいいねを付ける
    public function like(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if ($article->likeUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already liked.'], 409);
            }
            $article->likeUsers()->attach(Auth::id());
            return response()->json(['message' => 'Liked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    //記事のいいねを外す。
    public function unlike(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if (!$article->likeUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unliked.'], 409);
            }
            $article->likeUsers()->detach(Auth::id());
            return response()->json(['message' => 'Unliked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }
    
    
    //記事にブックマークを付ける
    public function bookmark(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if ($article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already bookmarked.'], 409);
            }
            $article->bookmarkUsers()->attach(Auth::id());
            return response()->json(['message' => 'Bookmarked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }
    
    
    //記事のブックマークを外す
    public function unbookmark(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if (!$article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unbookmarked.'], 409);
            }
            $article->bookmarkUsers()->detach(Auth::id());
            return response()->json(['message' => 'Unbookmarked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    //記事にアーカイブをつける
    public function archive(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if ($article->archiveUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already archived.'], 409);
            }
            $article->archiveUsers()->attach(Auth::id());
            return response()->json(['message' => 'Archived successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }    
    
    //記事のアーカイブを外す
    public function unarchive(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if (!$article->archiveUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unarchived.'], 409);
            }
            $article->archiveUsers()->detach(Auth::id());
            return response()->json(['message' => 'Unarchived successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    //記事にtrashをつける
    public function trash(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if ($article->trashUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already trashed.'], 409);
            }
            $article->trashUsers()->attach(Auth::id());
            return response()->json(['message' => 'Trshed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }    
    
    //記事のtrashを外す
    public function untrash(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            if (!$article->trashUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already untrashed.'], 409);
            }
            $article->trashUsers()->detach(Auth::id());
            return response()->json(['message' => 'Untrashed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////
    //フォローアンフォロー
    public function followAuthor(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            $author = $article->author;
            if ($author->followers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already followed.'], 409);
            }
            $author->followers()->attach(Auth::id());
            return response()->json(['message' => 'Followed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }    

    public function unfollowAuthor(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            $author = $article->author;
            if (!$author->followers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unfollowed.'], 409);
            }
            $author->followers()->detach(Auth::id());
            return response()->json(['message' => 'Unfollowed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }   

    //著者を捨てる
    public function trashAuthor(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            $author = $article->author;
            if ($author->trashedUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'This author was already trashed.'], 409);
            }
            $author->trashedUsers()->attach(Auth::id());
            return response()->json(['message' => 'You trashed this author successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }    

    public function untrashAuthor(Request $request) {
        try {
            $articleUrl = $request->query('articleUrl');
            Log::info('UUUUUUUUUUUUUUUUUUUUU articleUrl: ' . $articleUrl);
    
            $article = $this->getOrAddArticleFromUrl($articleUrl);
            $author = $article->author;
            if (!$author->trashedUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'This author was already untrashed.'], 409);
            }
            $author->trashedUsers()->detach(Auth::id());
            return response()->json(['message' => 'You untrashed this author successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }    
}
