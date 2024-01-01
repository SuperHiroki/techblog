<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\Author;
use App\Models\UserAuthorFollow;

class RecommendedAuthorsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'followers');
        $period = $request->input('period', 'week');

        $authors = Author::getSortedAuthors($sort, $period);

        return view('recommended-authors', compact('authors'));
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


