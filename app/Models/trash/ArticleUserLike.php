<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleUserLike extends Pivot {
    protected $fillable = ['article_id', 'user_id'];
    protected $table = 'article_user_like';
    public $timestamps = true;
}
