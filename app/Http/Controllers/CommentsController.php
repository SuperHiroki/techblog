<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Models\CommentLike;

class CommentsController extends Controller
{
    //コメント全てを取得する。
    public function index()
    {
        $comments = Comment::with('likes')->whereNull('parent_id')->paginate(6);
    
        // 各コメントに対していいねの詳細を追加
        foreach ($comments as $comment) {
            $comment->likedByAuthUser = $comment->likes->contains('user_id', Auth::id());
            $comment->likesCount = $comment->likes->count();
            $comment->repliesCount = $comment->replies()->count(); 
        }
    
        return view('comments.index', compact('comments'));
    }
}
