<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'description', 'link', 'good', 'bookmark', 'archive', 'author_id'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Add other necessary methods and properties
}
