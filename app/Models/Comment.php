<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    //このメソッドは、特定のコメントに対するすべての「いいね」のコレクションを返します。
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /*
    //このメソッドは、特定のコメントに「いいね」をしたすべてのユーザーのコレクションを返します。
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'comment_likes')
                    ->withTimestamps();
    }
    */
    
    //現在ログイン中のユーザがいいねをつけているかどうかのカラムを追加する。
    public function isLikedByAuthUser()
    {
        return $this->hasMany(CommentLike::class)->where('user_id', Auth::id())->exists();
    }
}
