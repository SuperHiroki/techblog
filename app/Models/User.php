<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //今のユーザがフォローしている著者一覧
    public function followedAuthors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'user_author_follows', 'user_id', 'author_id')
                    ->withTimestamps();
    }

    //今のユーザがいいね、ブックマーク、アーカイブしている記事一覧
    public function goodArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_good');
    }

    public function bookmarkArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_bookmark');
    }

    public function archiveArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_archive');
    }
}
