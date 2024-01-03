<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $table = 'comment_likes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['comment_id','user_id'];

    // このいいねが属するコメント。Commentインスタンス。
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // このいいねをしたユーザー。Userインスタンス。
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
