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
        $period = $request->input('period', 'week'); // 'week', 'month', 'year'

        switch ($sort) {
            case 'followers':
                $authors = Author::withFollowerCount()->orderBy('followers', 'desc')->get();
                break;
            case 'trending':
                $authors = Author::withFollowerCount($period)->orderBy('followers', 'desc')->get();
                break;
            case 'alphabetical':
                $authors = Author::orderBy('name')->get();
                break;
            default:
                $authors = Author::orderBy('name')->get();
                break;
        }

        $followedAuthors = Auth::user()->followedAuthors;
        return view('recommended-authors', compact('authors', 'followedAuthors'));
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


