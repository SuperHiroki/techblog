<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\UserAuthorFollow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecommendedAuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::all(); // 著者のデータを取得
        $followedAuthors = Auth::user()->followedAuthors; // ユーザーがフォローしている著者のIDを取得

        return view('recommended-authors', compact('authors', 'followedAuthors')); // ビューにデータを渡す
    }

    public function followAuthor(Author $author)
    {
        $user = Auth::user();
        $user->followedAuthors()->attach($author);
        return back();
    }

    public function unfollowAuthor(Author $author)
    {
        $user = Auth::user();
        $user->followedAuthors()->detach($author);
        return back();
    }
}


