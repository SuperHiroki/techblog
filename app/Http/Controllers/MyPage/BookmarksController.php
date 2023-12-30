<?php

namespace App\Http\Controllers\MyPage;

use App\Models\User;

use App\Http\Controllers\Controller;

class BookmarksController extends Controller
{
    public function index(User $user)
    {

        return view('my-page.bookmarks', compact('user', ));
    }
}