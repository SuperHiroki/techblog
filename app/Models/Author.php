<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Author extends Model
{
    protected $fillable = ['name', 'link', 'rss_link', 'thumbnail_url', 'favicon_url'];

    //著者が書いた記事一覧。Articleインスタンスのリスト。
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    //著者のフォロワー一覧。Userインスタンスのリスト。
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_author_follows')
                    ->withTimestamps();
    }

    //ソート
    public static function getSortedAuthors($sort, $period = null, $user = null)
    {
        //クエリの生成
        $query = self::query();
    
        //ユーザが渡されたらそのユーザがフォローしている著者に絞る（マイページに表示するもの）。
        if ($user) {
            $query->whereIn('authors.id', $user->followedAuthors->pluck('id'));
        }

        //期間の取得
        $dateFrom = now();
        if ($sort=='trending_followers' || $sort=='trending_articles') {
            switch ($period) {
                case 'week':
                    $dateFrom->subWeek();
                    break;
                case 'month':
                    $dateFrom->subMonth();
                    break;
                case 'year':
                    $dateFrom->subYear();
                    break;
            }
        }elseif($sort=='followers' || $sort=='articles' || $sort=='alphabetical'){
            $dateFrom = null;
        }

        // フォロワー数のサブクエリ。user_author_followsテーブルからfollowers_countカラムを生み出す。
        $dateCondition = $dateFrom ? "created_at >= '{$dateFrom->toDateTimeString()}'" : "1=1";// 期間がnullの場合は常に真を返す
        $followersCountSubQuery = DB::table('user_author_follows')
            ->select('author_id', DB::raw("COALESCE(COUNT(CASE WHEN {$dateCondition} THEN 1 ELSE NULL END), 0) as followers_count"))//フォロワー数が0の時に0にならないのはなぜ？
            ->groupBy('author_id');

        //記事数のサブクエリ。articlesテーブルからarticles_countカラムを生み出す。
        $dateConditionArticleCreatedDate = $dateFrom ? "created_date >= '{$dateFrom->toDateTimeString()}' AND created_date IS NOT NULL" : "1=1";// 期間がnullの場合は常に真を返す
        $articlesCountSubQuery = DB::table('articles')
            ->select('author_id', DB::raw("COALESCE(COUNT(CASE WHEN {$dateConditionArticleCreatedDate} THEN 1 ELSE NULL END), 0) as articles_count"))//フォロワー数が0の時に0にならないのはなぜ？
            ->groupBy('author_id');

        // サブクエリをメインクエリに結合。followers_countカラムとarticles_countカラムを追加する。
        $query->select('authors.*', 'fc.followers_count', 'ac.articles_count')
            ->leftJoinSub($followersCountSubQuery, 'fc', 'authors.id', '=', 'fc.author_id')
            ->leftJoinSub($articlesCountSubQuery, 'ac', 'authors.id', '=', 'ac.author_id');

        // 並び替え
        if ($sort === 'alphabetical') {
            $query->orderBy('name');
        } elseif ($sort=='followers' || $sort=='trending_followers'){
            $query->orderBy('fc.followers_count', 'desc');
        } elseif ($sort=='articles' || $sort=='trending_articles'){
            $query->orderBy('ac.articles_count', 'desc');
        }
    
        //現在ログイン中のユーザが、それぞれの著者に対してフォローしているかどうかのカラムを追加。「WHERE author_id = authors.id AND user_id = {$loggedInUserId}」の部分で存在すれば現在のログイン中のユーザがそのレコードの著者をフォローしているということになる。
        if (Auth::check()) {
            $loggedInUserId = Auth::id();
            $query->addSelect(DB::raw("EXISTS (SELECT 1 FROM user_author_follows WHERE author_id = authors.id AND user_id = {$loggedInUserId}) as is_followed"));
        }

        return $query->get();
    }
}
