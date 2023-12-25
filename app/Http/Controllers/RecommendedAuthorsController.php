<?php

namespace App\Http\Controllers;

use App\Models\Author; // 必要に応じて追加

class RecommendedAuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::all(); // 著者のデータを取得
        return view('recommended-authors', compact('authors')); // ビューにデータを渡す
    }
}

