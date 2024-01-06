<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenGetRedirectController extends Controller
{
    public function index()
    {
        return view('api-token-get-redirect');
    }
}
