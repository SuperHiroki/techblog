<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Article;

class RecentArticlesController extends Controller
{
    public function index(Request $request, User $user, string $days = '7')
    {
        $sort = $request->input('sort', 'likes');
        $period = $request->input('period', 'week');

        $articles = Article::sortBy($sort, $period, $user, 'recent-articles', $days)->paginate(5);
        
        return view('my-page.recent-articles', compact('user', 'articles'));
    }
}