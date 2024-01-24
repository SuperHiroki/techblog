<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use App\Models\Article;
use App\Models\User;

use App\Helpers\ParameterValidationHelper;

class RecommendedArticlesController extends Controller
{
    //記事一覧を表示する。
    public function index(Request $request)
    {
        //パラメタがない場合、デフォルトのパラメタにリダイレクト
        if (!$request->has('sort')) {
            return redirect()->route(Route::currentRouteName(), ['sort' => 'likes']);
        }

        try {
            //バリデーションチェック
            ParameterValidationHelper::validateParametersSortArticles($request);
            //ソート
            $articles = Article::sortBy(sort: $request->input('sort'),  
                                        period: $request->input('period'),
                                        keywords: $request->input('keywords'))->paginate(15);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    
        return view('recommended-articles', compact('articles'));
    }    
}

