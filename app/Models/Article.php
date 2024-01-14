<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Article extends Model
{
    protected $fillable = ['title', 'description', 'link', 'author_id', 'thumbnail_url', 'favicon_url', 'created_date', 'updated_date'];

    // 記事を作成するメソッド（ドメインチェック含む）
    public static function createWithHasAuthorCheck($link, $metaData, $pubDate = null)
    {
        // 既に存在するリンクかチェック
        if (self::existsWithLink($link)) {
            // 既に存在する場合は例外を投げるか、もしくはnullを返して処理を中断
            throw new \Exception('The article with the provided link already exists.');
        }

        //リンクに対応する著者が存在するかどうかの確認。
        $author = self::hasAuthorCheck($link);

        if (!$author) {
            throw new \Exception('The provided link domain does not match any author domain.');
        }

        return self::createArticle($link, $metaData, $author, $pubDate);
    }

    // 記事を更新するメソッド
    public static function updateArticle($link, $metaData, $pubDate = null)
    {
        $article = self::where('link', $link)->first();

        if (!$article) {
            throw new \Exception('No article found with the provided link.');
        }

        $updateFields = [
            'title' => $metaData['title'] ?? $link,//何も取得できなければリンクを使う(Notionはこのようにしていたので真似する)。
            'description' => $metaData['description'],
            'thumbnail_url' => $metaData['thumbnail_url'],
            'favicon_url' => $metaData['favicon_url'],
        ];

        // created_date カラムの更新（nullでない場合のみ）
        if (!is_null($pubDate)) {
            $updateFields['created_date'] = $pubDate;
        }
        
        $article->update($updateFields);
    }

    // 記事が既に存在するかどうかをチェックするメソッド
    public static function existsWithLink($link)
    {
        return self::where('link', $link)->exists();
    }    

    //リンクに対応する著者が存在するかどうかの確認。
    public static function hasAuthorCheck($link)
    {
        //ドメインチェック
        $articleDomain = parse_url($link, PHP_URL_HOST);
        $author = Author::where('link_common', '=', "{$articleDomain}")->first(); // 「Author::where('link_common', 'like', "%{$articleDomain}%")->first()」だと、「zenn.dev/」の一致で著者と判断してしまう可能性があるのでダメ。
        if ($author) {
            return $author;
        }

        //authorsテーブルのlink_commonカラムのいずれかを、$linkが先頭に含んでいれば、trueを返すロジックを書きたい。
        $authors = Author::all();
        foreach ($authors as $author) {
            // link_commonが$linkのどこかに一致するかチェック
            if (strpos($link, $author->link_common) !== false) { // 先頭部分を確認するなら「if (strpos($link, $author->link_common) === 0)」を使うのがいいと考える。
                return $author;
            }
        }

        return null;
    }

    //記事レコードを作成する
    public static function createArticle($link, $metaData, $author, $pubDate = null)
    {
        return self::create([
            'title' => $metaData['title'] ?? $link,//何も取得できなければリンクを使う(Notionはこのようにしていたので真似する)。
            'description' => $metaData['description'],
            'thumbnail_url' => $metaData['thumbnail_url'],
            'favicon_url' => $metaData['favicon_url'],
            'link' => $link,
            'author_id' => $author->id,
            'created_date' => $pubDate,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////
    //この記事を書いた著者。Authorインスタンス。
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    //この記事にいいね（ブックマーク、アーカイブ）をつけたユーザ一覧。Userインスタンスのリスト。
    public function likeUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_like')
                    ->withTimestamps();
    }

    public function bookmarkUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_bookmark')
                    ->withTimestamps();
    }

    public function archiveUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_archive')
                    ->withTimestamps();
    }

    // 記事の並び替え
    public function scopeSortBy($query, $sort, $period, $user = null, $action = null, $spanFilter = null)
    {
        $query->with([
            //これはなくても機能するが、Lagzy LoadによるN+1問題を防ぐために、ここでEager Loadしておく。
            'author',
        ]);

        // それぞれの記事に対して、いいね（ブックマーク、アーカイブ）をしているかどうか。現在ログイン中のユーザを使う。
        $currentUser = auth()->user();
        if ($currentUser) {
            // 現在のユーザーがいいねをしたかどうかを判定するサブクエリ
            $likedByCurrentUserSubQuery = DB::table('article_user_like')
                ->select(DB::raw(1))
                ->where('user_id', $currentUser->id)
                ->whereColumn('article_id', 'articles.id')
                ->limit(1);

            // 現在のユーザーがブックマークをしたかどうかを判定するサブクエリ
            $bookmarkedByCurrentUserSubQuery = DB::table('article_user_bookmark')
                ->select(DB::raw(1))
                ->where('user_id', $currentUser->id)
                ->whereColumn('article_id', 'articles.id')
                ->limit(1);

            // 現在のユーザーがアーカイブをしたかどうかを判定するサブクエリ
            $archivedByCurrentUserSubQuery = DB::table('article_user_archive')
                ->select(DB::raw(1))
                ->where('user_id', $currentUser->id)
                ->whereColumn('article_id', 'articles.id')
                ->limit(1);

            // サブクエリをメインクエリに結合
            $query->addSelect([
                'liked_by_current_user' => $likedByCurrentUserSubQuery,
                'bookmarked_by_current_user' => $bookmarkedByCurrentUserSubQuery,
                'archived_by_current_user' => $archivedByCurrentUserSubQuery
            ]);
        }

        //マイページに表示させる記事一覧はフィルターが必要。マイページのユーザを使う。
        if ($user && $action) {
            switch ($action) {
                case 'likes':
                    $query->whereHas('likeUsers', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
                    break;
                case 'bookmarks':
                    $query->whereHas('bookmarkUsers', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
                    break;
                case 'archives':
                    $query->whereHas('archiveUsers', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
                    break;
                case 'recent-articles':
                    if ($spanFilter) {
                        $dateFrom = now()->subDays(intval($spanFilter));
    
                        // マイページのユーザーがフォローしている著者の記事のみを取得。フォロー中の著者で絞らないとおすすめ記事一覧と変わらない。
                        $query->whereHas('author', function ($q) use ($user, $dateFrom) {
                                $q->whereHas('followers', function ($q) use ($user) {
                                    $q->where('users.id', $user->id);
                                });
                            })
                        //記事の作成日が直近?日間で絞る。
                              ->where('articles.created_date', '>=', $dateFrom);
                    }
                    break;
            }
        }
    
        // 通常の並び替え処理
        switch ($sort) {
            case 'likes':
                $query->withCount('likeUsers')->orderBy('like_users_count', 'desc')->orderBy('id');
                break;
            case 'bookmarks':
                $query->withCount('bookmarkUsers')->orderBy('bookmark_users_count', 'desc')->orderBy('id');
                break;
            case 'archives':
                $query->withCount('archiveUsers')->orderBy('archive_users_count', 'desc')->orderBy('id');
                break;
            case 'newest':
                $query->orderBy('created_date', 'desc')->orderBy('id');
                break;
            default:
                if (str_starts_with($sort, 'trending_')) {
                    $baseRelation = Str::singular(str_replace('trending_', '', $sort));//like, bookmark, archive
                    $relation = $baseRelation . 'Users';
                    $pivotTable = 'article_user_' . $baseRelation;
                    $countColumn = $baseRelation . "_users_count";
    
                    $query->withCount([$relation => function ($q) use ($period, $pivotTable) {
                                if ($period === 'week') $q->where($pivotTable . '.created_at', '>=', now()->subWeek());
                                elseif ($period === 'month') $q->where($pivotTable . '.created_at', '>=', now()->subMonth());
                                elseif ($period === 'year') $q->where($pivotTable . '.created_at', '>=', now()->subYear());
                            }])
                            ->orderBy($countColumn, 'desc')->orderBy('id');
                }
                break;
        }

        return $query;
    }
    
}
