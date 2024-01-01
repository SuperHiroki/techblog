<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Author extends Model
{
    protected $fillable = ['name', 'link', 'rss_link', 'thumbnail_url', 'favicon_url'];

    //著者が書いた記事
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    //著者のフォロワー一覧
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_author_follows')
                    ->withTimestamps();
    }

    //ソート
    public static function getSortedAuthors($sort, $period = 'week', $user = null)
    {
        $query = self::query();
    
        //ユーザが渡されたらそのユーザがフォローしている著者に絞る（マイページに表示するもの）。
        if ($user) {
            $query->whereIn('authors.id', $user->followedAuthors->pluck('id'));
        }
    
        if ($sort === 'alphabetical') {
            $query->orderBy('name');
        } else {
            $dateFrom = now();
    
            if ($sort === 'trending') {
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
            } else {
                $dateFrom = null;
            }
    
            $rawCountQuery = $dateFrom 
                ? "COALESCE(COUNT(CASE WHEN user_author_follows.created_at >= '{$dateFrom->toDateTimeString()}' THEN 1 END), 0) as followers"
                : "COALESCE(COUNT(user_author_follows.author_id), 0) as followers";
    
            $query->select('authors.*', DB::raw($rawCountQuery))
                  ->leftJoin('user_author_follows', 'authors.id', '=', 'user_author_follows.author_id')
                  ->groupBy('authors.id')
                  ->orderBy('followers', 'desc');
        }
    
        if (Auth::check()) {
            $loggedInUserId = Auth::id();
            $query->addSelect(DB::raw("EXISTS (SELECT 1 FROM user_author_follows WHERE author_id = authors.id AND user_id = {$loggedInUserId}) as is_followed"));
        }
    
        return $query->get();
    }
    
}
