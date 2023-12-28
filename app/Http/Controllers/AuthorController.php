<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OgImageHelper;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_admin) {
                return response('This is author edit page. Please login as admin.', 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $authors = Author::withFollowerCount()
                         ->withCount('articles')
                         ->get();
        foreach ($authors as $author) {
            $author->latestArticle = $author->articles()->latest()->first(); // 最新の記事を取得
        }
        return view('authors.index', compact('authors'));
    }
    

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'link' => 'required|url'
        ]);
    
        // リンクからメタデータを取得
        $metaData = OgImageHelper::getMetaData($validatedData['link']);
        if ($metaData) {
            $validatedData = array_merge($validatedData, $metaData);
        }
    
        Author::create($validatedData);
    
        return redirect()->route('authors.index');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index');
    }
}
