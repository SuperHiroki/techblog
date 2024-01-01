<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Article;
use App\Models\Author;

use App\Http\Controllers\Controller;

class FollowedAuthorsController extends Controller
{
    public function index(Request $request, User $user)
    {
        $sort = $request->input('sort', 'followers');
        $period = $request->input('period', 'week');

        $authors = Author::getSortedAuthors($sort, $period, $user);

        return view('my-page.followed-authors', compact('authors', 'user'));
    }
}