<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersListController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->paginate(20);

        return view('users-list', compact('users'));
    }
}
