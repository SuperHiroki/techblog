<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Article;

use App\Http\Controllers\Controller;

use App\Helpers\ParameterValidationHelper;

class TrashedArticlesController extends Controller
{
    //マイページのユーザがアーカイブしている記事一覧を取得する。
    public function index(Request $request, User $user)
    {
        //パラメタがない場合、デフォルトのパラメタにリダイレクト
        if (!$request->has('sort')) {
            return redirect()->route(Route::currentRouteName(), ['user' => $user->id, 'sort' => 'newest']);
        }

        try {
            //バリデーションチェック
            ParameterValidationHelper::validateParametersSortArticles($request);
            //ソート
            $articles = Article::sortBy(sort: $request->input('sort'), 
                                        period: $request->input('period'),
                                        keywords: $request->input('keywords'),
                                        user: $user, 
                                        action: 'trashes', 
                                        isTrashExcluded: false)->paginate(15);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
        
        return view('my-page.trashed-articles', compact('user', 'articles'));
    }
}
