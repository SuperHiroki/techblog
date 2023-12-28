<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\UserAuthorFollow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RecommendedAuthorsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'followers');
        $period = $request->input('period', 'week');

        switch ($sort) {
            case 'followers':
                //下記二つは同じ処理である
                //$authors = Author::query()->withCount('followers')->orderBy('followers_count', 'desc')->get(); //{{ $author->followers_count }}を使う必要があるかな。
                $authors = Author::withFollowerCount()->orderBy('followers', 'desc')->get();
                break;
            case 'trending':
                $authors = Author::withFollowerCount($period)->orderBy('followers', 'desc')->get();
                break;
            case 'alphabetical':
                $authors = Author::orderBy('name')->get();
                break;
        }

        $user = Auth::user();
        $followedAuthors = $user ? $user->followedAuthors : collect([]);
        return view('recommended-authors', compact('authors', 'followedAuthors'));
    }

    public function followAuthor(Author $author)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $user->followedAuthors()->attach($author);
        return back();
    }

    public function unfollowAuthor(Author $author)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $user->followedAuthors()->detach($author);
        return back();
    }
}


