<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    // テーブル名を指定
    protected $table = 'comment_likes';

    // モデルが使用するデフォルトのプライマリキーのタイプ
    protected $primaryKey = 'id';

    // タイムスタンプを自動的に管理するかどうか
    public $timestamps = true;

    // マスアサインメントを許可するフィールド
    protected $fillable = [
        'comment_id',
        'user_id'
    ];

    // このいいねが属するコメント
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // このいいねをしたユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
