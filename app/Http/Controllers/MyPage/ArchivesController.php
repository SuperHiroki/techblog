<?php

namespace App\Http\Controllers\MyPage;

use App\Models\User;

use App\Http\Controllers\Controller;

class ArchivesController extends Controller
{
    public function index(User $user)
    {

        return view('my-page.archives', compact('user', ));
    }
}
