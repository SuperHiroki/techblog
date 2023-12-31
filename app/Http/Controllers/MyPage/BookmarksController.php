<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Article;

use App\Http\Controllers\Controller;

class BookmarksController extends Controller
{
    public function index(Request $request, User $user)
    {
        $sort = $request->input('sort', 'likes');
        $period = $request->input('period', 'week');

        $articles = Article::sortBy($sort, $period, $user, 'bookmarks')->paginate(5);
        
        return view('my-page.bookmarks', compact('user', 'articles'));
    }
}