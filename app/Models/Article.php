<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = ['title', 'description', 'link', 'author_id', 'thumbnail_url', 'favicon_url'];

    //この記事を書いた著者
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    //この記事にいいね、ブックマーク、アーカイブをつけたユーザ一覧
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
        // それぞれの記事に対して、いいね（ブックマーク、アーカイブ）をしているかどうか。現在ログイン中のユーザを使う。
        $currentUser = auth()->user();
        if ($currentUser) {
            $query->with([
                'likeUsers' => function ($q) use ($currentUser) {
                    $q->where('users.id', $currentUser->id)->select('users.id');
                },
                'bookmarkUsers' => function ($q) use ($currentUser) {
                    $q->where('users.id', $currentUser->id)->select('users.id');
                },
                'archiveUsers' => function ($q) use ($currentUser) {
                    $q->where('users.id', $currentUser->id)->select('users.id');
                }
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
                            })->where('articles.created_date', '>=', $dateFrom);
                        });
                    }
                    break;
            }
        }
    
        // 通常の並び替え処理
        switch ($sort) {
            case 'likes':
                $query->withCount('likeUsers')->orderBy('like_users_count', 'desc');
                break;
            case 'bookmarks':
                $query->withCount('bookmarkUsers')->orderBy('bookmark_users_count', 'desc');
                break;
            case 'archives':
                $query->withCount('archiveUsers')->orderBy('archive_users_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_date', 'desc');
                break;
            default:
                if (str_starts_with($sort, 'trending_')) {
                    $baseRelation = Str::singular(str_replace('trending_', '', $sort));
                    $relation = $baseRelation . 'Users';
                    $pivotTable = 'article_user_' . $baseRelation;
                    $countColumn = $baseRelation . "_users_count";
    
                    $query->withCount([$relation => function ($query) use ($period, $pivotTable) {
                                if ($period === 'week') $query->where($pivotTable . '.created_at', '>=', now()->subWeek());
                                elseif ($period === 'month') $query->where($pivotTable . '.created_at', '>=', now()->subMonth());
                                elseif ($period === 'year') $query->where($pivotTable . '.created_at', '>=', now()->subYear());
                            }])
                            ->orderBy($countColumn, 'desc');
                }
                break;
        }
    
        return $query;
    }
    
}
