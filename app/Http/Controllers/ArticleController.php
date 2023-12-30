<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OgImageHelper;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_admin) {
                return response('This is article edit page. Please login as admin.', 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        // 著者、いいね数、ブックマーク数、アーカイブ数を含めて記事を取得
        $articles = Article::with(['author', 'likeUsers', 'bookmarkUsers', 'archiveUsers'])->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'link' => 'required|url'
        ]);
    
        // Fetch HTML content and metadata
        $metaData = OgImageHelper::getMetaData($validatedData['link']);
        if (!$metaData) {
            return back()->withErrors(['link' => 'Unable to fetch metadata from the link.']);
        }
    
        // Extract the domain from the article link
        $articleDomain = parse_url($validatedData['link'], PHP_URL_HOST);
    
        // Find the author by matching the domain
        $author = Author::where('link', 'LIKE', "%{$articleDomain}%")->first();
        if (!$author) {
            return back()->withErrors(['link' => 'The provided link domain does not match any author domain.']);
        }
    
        // Create the article with the fetched and validated data
        Article::create([
            'title' => $metaData['title'],
            'description' => $metaData['description'],
            'thumbnail_url' => $metaData['thumbnail_url'],
            'favicon_url' => $metaData['favicon_url'],
            'link' => $validatedData['link'],
            'author_id' => $author->id,
        ]);
    
        return redirect()->route('articles.index');
    } 

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }
}

