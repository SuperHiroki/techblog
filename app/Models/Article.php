<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
