<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Article;

use App\Http\Controllers\Controller;

use App\Helpers\OgImageHelper;

class ArticlesAsyncActionController extends Controller
{
    ////////////////////////////////////////////////////////////////////////////////
    //記事にいいねを付ける
    public function likeArticleFromArticleId(Article $article){
        try {
            if ($article->likeUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already liked.'], 409);
            }
            $article->likeUsers()->attach(Auth::id());
            return response()->json(['message' => "{$article->title}にいいねをつけました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    //記事からいいねを外す
    public function unlikeArticleFromArticleId(Article $article){
        try {
            if (!$article->likeUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unliked.'], 409);
            }
            $article->likeUsers()->detach(Auth::id());
            return response()->json(['message' => "{$article->title}からいいねを削除しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    // 記事にブックマークを追加
    public function bookmarkArticleFromArticleId(Article $article){
        try {
            if ($article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already bookmarked.'], 409);
            }
            $article->bookmarkUsers()->attach(Auth::id());
            return response()->json(['message' => "{$article->title}にブックマークを追加しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    // 記事からブックマークを外す
    public function unbookmarkArticleFromArticleId(Article $article){
        try {
            if (!$article->bookmarkUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unbookmarked.'], 409);
            }
            $article->bookmarkUsers()->detach(Auth::id());
            return response()->json(['message' => "{$article->title}からブックマークを削除しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    // 記事にアーカイブを追加
    public function archiveArticleFromArticleId(Article $article){
        try {
            if ($article->archiveUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already archived.'], 409);
            }
            $article->archiveUsers()->attach(Auth::id());
            return response()->json(['message' => "{$article->title}にアーカイブを追加しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    // 記事からアーカイブを外す
    public function unarchiveArticleFromArticleId(Article $article){
        try {
            if (!$article->archiveUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already unarchived.'], 409);
            }
            $article->archiveUsers()->detach(Auth::id());
            return response()->json(['message' => "{$article->title}からアーカイブを削除しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    //記事をゴミ箱に入れる。
    public function trashArticleFromArticleId(Article $article){
        try {
            if ($article->trashUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already trashed.'], 409);
            }
            $article->trashUsers()->attach(Auth::id());
            return response()->json(['message' => "{$article->title}をゴミ箱にいれました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }

    // 記事からアーカイブを外す
    public function untrashArticleFromArticleId(Article $article){
        try {
            if (!$article->trashUsers()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'Already untrashed.'], 409);
            }
            $article->trashUsers()->detach(Auth::id());
            return response()->json(['message' => "{$article->title}をゴミ箱から復元しました。"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "{$e->getMessage()}"], 500);
        }
    }
}