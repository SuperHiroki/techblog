<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Article;

use App\Http\Controllers\Controller;

class FollowedAuthorsController extends Controller
{
    public function index(User $user)
    {

        return view('my-page.followed-authors', compact('user', ));
    }
}