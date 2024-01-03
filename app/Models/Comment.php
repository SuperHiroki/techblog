<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'parent_id'];

    //コメントを書いたユーザ。Userインスタンス。
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //コメントに対する返信（コメント）。Commentインスタンス。
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    //コメントに対するすべての「いいね」のコレクションを返します。CommentLikeインスタンスのリストが返るのでuser_idは取得できるけど、Userインスタンスを直接使えないことに注意。
    //一つ下のlikedUsersメソッドよりも情報量がすくない。
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }
    /*
    //コメントに「いいね」をしたすべてのユーザーのコレクションを返します。Userインスタンスのリスト。
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
