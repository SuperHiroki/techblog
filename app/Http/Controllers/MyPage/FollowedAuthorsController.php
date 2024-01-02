<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Article;
use App\Models\Author;

use App\Http\Controllers\Controller;

use App\Helpers\ParameterValidationHelper;

class FollowedAuthorsController extends Controller
{
    public function index(Request $request, User $user)
    {
        //パラメタがない場合、デフォルトのパラメタにリダイレクト
        if (!$request->has('sort')) {
            return redirect()->route(Route::currentRouteName(), ['user' => $user->id, 'sort' => 'followers']);
        }

        try {
            //バリデーションチェック
            ParameterValidationHelper::validateParametersSortAuthors($request);
            //ソート
            $authors = Author::getSortedAuthors($request->input('sort'), $request->input('period', null), $user);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return view('my-page.followed-authors', compact('authors', 'user'));
    }
}