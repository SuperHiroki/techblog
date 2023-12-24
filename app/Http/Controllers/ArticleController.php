<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'link' => 'required|url',
            'good' => 'sometimes|accepted',
            'bookmark' => 'sometimes|accepted',
            'archive' => 'sometimes|accepted'
        ]);
    
        // チェックボックスが未チェックの場合、値を0に設定
        $validatedData['good'] = $request->has('good') ? 1 : 0;
        $validatedData['bookmark'] = $request->has('bookmark') ? 1 : 0;
        $validatedData['archive'] = $request->has('archive') ? 1 : 0;
    
        // ArticleのリンクのドメインをPHPで抽出
        $articleDomain = parse_url($validatedData['link'], PHP_URL_HOST);

        Log::info('IIIIIIIIIIIIIIIIIIIIIIIII Your debug message');
    
        // SQLクエリでLIKE演算子を使用してドメインが一致するAuthorを探す
        $author = Author::select('id')
                         ->where('link', 'LIKE', "%{$articleDomain}%")
                         ->first();
    
        Log::info('HHHHHHHHHHHHHHHHHHHHHHHHHHHH Your debug message');

        if (!$author) {
            Log::info('JJJJJJJJJJJJJJJJJJJJJJJ Your debug message');
            return back()->withErrors(['link' => 'The provided link domain does not match any author domain.']);
        }
    
        // 一致したAuthorのIDをauthor_idとして設定
        $validatedData['author_id'] = $author->id;
    
        Article::create($validatedData);
    
        return redirect()->route('articles.index');
    }    

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }
}

