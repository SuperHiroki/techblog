<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Author extends Model
{
    protected $fillable = ['name', 'link', 'link_common', 'rss_link', 'thumbnail_url', 'favicon_url'];

    //著者を作成する。
    public static function createAuthor($link, $link_common, $metaData)
    {
        // リンクに一致する著者がすでにいたら例外を投げる。
        $author = self::where('link', $link)->first();
        if ($author) {
            throw new \Exception('The author already exists.');
        }

        $author = new self();

        $author->name = $metaData['title'] ?? $link;//何も取得できなければリンクを使う(Notionはこのようにしていたので真似する)。
        $author->link = $link; 
        $author->link_common = $link_common; 
        $author->rss_link = $metaData['rss_link'] ?? null;
        $author->thumbnail_url = $metaData['thumbnail_url'] ?? null;
        $author->favicon_url = $metaData['favicon_url'] ?? null;

        $author->save();

        return $author;
    }

    // 著者情報を更新するメソッド
    public static function updateAuthor($link, $metaData)
    {
        // リンクに一致する著者を検索
        $author = self::where('link', $link)->first();

        // 著者が見つからない場合は例外を投げる
        if (!$author) {
            throw new \Exception('No author found with the provided link.');
        }

        // メタデータを用いて著者情報を更新
        $author->update([
            'name' => $metaData['title'] ?? $link,//何も取得できなければリンクを使う(Notionはこのようにしていたので真似する)。
            'rss_link' => $metaData['rss_link'] ?? null,
            'thumbnail_url' => $metaData['thumbnail_url'] ?? null,
            'favicon_url' => $metaData['favicon_url'] ?? null
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
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

    //著者を捨てたユーザ一覧。Userインスタンスのリスト。
    public function trashedUsers()
    {
        return $this->belongsToMany(User::class, 'user_author_trashes')
                    ->withTimestamps();
    }

    //ソート
    public static function getSortedAuthors($sort, $period = null, $user = null, $isTrashExcluded = false, $action = "followed")
    {
        //クエリの生成
        $query = self::query();
    
        //ユーザが渡されたらそのユーザがフォローしている著者に絞る（マイページに表示するもの）。
        if ($user) {
            if($action == "followed"){
                $query->whereIn('authors.id', $user->followedAuthors->pluck('id'));
            }else if($action == "trashed"){
                $query->whereIn('authors.id', $user->trashedAuthors->pluck('id'));
            }
            //ゴミ箱のものを表示しない場合。
            if($isTrashExcluded){
                $query->whereRaw("NOT EXISTS (SELECT 1 FROM user_author_trashes WHERE author_id = authors.id AND user_id = ?)", [$user->id]);
            }
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
            $query->orderBy('name')->orderBy('id');
        } elseif ($sort=='followers' || $sort=='trending_followers'){
            $query->orderBy('fc.followers_count', 'desc')->orderBy('id');
        } elseif ($sort=='articles' || $sort=='trending_articles'){
            $query->orderBy('ac.articles_count', 'desc')->orderBy('id');
        }
    
        //現在ログイン中のユーザについて。
        if (Auth::check()) {
            $loggedInUserId = Auth::id();
            //現在ログイン中のユーザが、それぞれの著者に対してフォローしているかどうかのカラムを追加。「WHERE author_id = authors.id AND user_id = {$loggedInUserId}」の部分で存在すれば現在のログイン中のユーザがそのレコードの著者をフォローしているということになる。
            $query->addSelect(DB::raw("EXISTS (SELECT 1 FROM user_author_follows WHERE author_id = authors.id AND user_id = {$loggedInUserId}) as is_followed"));
            //現在ログイン中のユーザが、それぞれの著者に対してtrashしているかどうかのカラムを追加。
            $query->addSelect(DB::raw("EXISTS (SELECT 1 FROM user_author_trashes WHERE author_id = authors.id AND user_id = {$loggedInUserId}) as trashed_by_current_user"));
        }

        return $query;
    }
}
