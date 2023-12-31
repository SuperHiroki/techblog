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

    // 急上昇ソートの並び替え。
    public function scopeApplyTrendingSort($query, $sort, $period)
    {
        if (str_starts_with($sort, 'trending_')) {
            $baseRelation = Str::singular(str_replace('trending_', '', $sort));
            $relation = $baseRelation . 'Users';
            $pivotTable = 'article_user_' . $baseRelation;
            $countColumn = $baseRelation . "_users_count";

            return $query->withCount([$relation => function ($query) use ($period, $pivotTable) {
                        if ($period === 'week') $query->where($pivotTable . '.created_at', '>=', now()->subWeek());
                        elseif ($period === 'month') $query->where($pivotTable . '.created_at', '>=', now()->subMonth());
                        elseif ($period === 'year') $query->where($pivotTable . '.created_at', '>=', now()->subYear());
                    }])
                    ->orderBy($countColumn, 'desc');
        }
    }
}
