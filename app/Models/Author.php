<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'link', 'rss_link'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Add other necessary methods and properties
}
