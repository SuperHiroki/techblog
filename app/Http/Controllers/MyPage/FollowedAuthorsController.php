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
    //マイページのユーザがフォローしている著者一覧を表示する。
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
            $authors = Author::getSortedAuthors(sort: $request->input('sort'), 
                                                period: $request->input('period'), 
                                                user: $user, 
                                                isTrashExcluded: true, 
                                                action: "followed")->paginate(20);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return view('my-page.followed-authors', compact('authors', 'user'));
    }
}