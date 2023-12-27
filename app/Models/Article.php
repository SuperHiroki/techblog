<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'description', 'link', 'author_id', 'thumbnail_url', 'favicon_url'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'article_user_good');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'article_user_bookmark');
    }

    public function archives()
    {
        return $this->belongsToMany(User::class, 'article_user_archive');
    }
}
