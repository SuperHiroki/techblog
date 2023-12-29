<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'link', 'rss_link', 'thumbnail_url', 'favicon_url'];

    //著者が書いた記事
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    //withFollowerCount()（引数なし）と同じ処理になる。
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_author_follows')
                    ->withTimestamps();
    }

    //引数がなければfollowers()と同じ処理になる。
    public static function withFollowerCount($period = 'all')
    {
        $dateFrom = now();
    
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
            default:
                $dateFrom = null;
                break;
        }
    
        $rawCountQuery = $dateFrom 
            ? "COALESCE(COUNT(CASE WHEN user_author_follows.created_at >= '{$dateFrom->toDateTimeString()}' THEN 1 END), 0) as followers"
            : "COALESCE(COUNT(user_author_follows.author_id), 0) as followers";
    
        return self::select('authors.*', DB::raw($rawCountQuery))
                    ->leftJoin('user_author_follows', 'authors.id', '=', 'user_author_follows.author_id')
                    ->groupBy('authors.id');
    }
}