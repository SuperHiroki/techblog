<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenRedirectController extends Controller
{
    public function get()
    {
        return view('api-token-get-redirect');
    }

    public function throwAway()
    {
        return view('api-token-throw-away-redirect');
    }
}
