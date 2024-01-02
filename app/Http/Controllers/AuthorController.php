<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Models\Author;

use App\Helpers\OgImageHelper;
use App\Helpers\ParameterValidationHelper;

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

    public function index(Request $request)
    {
        // followersとarticlesの件数を集計しつつ、著者一覧を取得する
        $authors = Author::with(['articles', 'followers'])
                        ->withCount(['articles', 'followers'])
                        ->get();

        // 最新の記事を取得
        foreach ($authors as $author) {
            $author->latestArticle = $author->articles()->latest()->first();
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
