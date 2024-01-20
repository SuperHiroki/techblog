<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Article;
use App\Models\Author;

use App\Http\Controllers\Controller;

use App\Helpers\OgImageHelper;

class AuthorAsyncActionController extends Controller
{
    ////////////////////////////////////////////////////////////////////////////////
    public function followAuthorFromAuthorId(Author $author){
        if (!Auth::check()) {
            return response()->json(['message' => "You are not logged in."], 500);
        }
        $user = Auth::user();
        $user->followedAuthors()->attach($author);
        return response()->json(['message' => "{$author->name} をフォローしました。"], 200);
    }

    public function unfollowAuthorFromAuthorId(Author $author){
        if (!Auth::check()) {
            return response()->json(['message' => "You are not logged in."], 500);
        }
        $user = Auth::user();
        $user->followedAuthors()->detach($author);
        return response()->json(['message' => "{$author->name} のフォローを外しました。"], 200);
    }
    ////////////////////////////////////////////////////////////////////////////////
    public function trashAuthorFromAuthorId(Author $author){
        if (!Auth::check()) {
            return response()->json(['message' => "You are not logged in."], 500);
        }
        $user = Auth::user();
        $user->trashedAuthors()->attach($author);
        return response()->json(['message' => "{$author->name} をゴミ箱に入れました。"], 200);
    }

    public function untrashAuthorFromAuthorId(Author $author){
        if (!Auth::check()) {
            return response()->json(['message' => "You are not logged in."], 500);
        }
        $user = Auth::user();
        $user->trashedAuthors()->detach($author);
        return response()->json(['message' => "{$author->name} をゴミ箱から復元しました。"], 200);
    }
}