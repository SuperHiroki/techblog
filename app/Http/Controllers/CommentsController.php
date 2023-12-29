<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::with('replies')->whereNull('parent_id')->get();
        return view('comments.index', compact('comments'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'body' => 'required|max:256',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = new Comment();
        $comment->body = $validatedData['body'];
        $comment->user_id = Auth::id();
        $comment->parent_id = $validatedData['parent_id'] ?? null;
        $comment->save();

        return redirect()->back();
    }

    public function update(Request $request, Comment $comment)
    {
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return redirect()->route('login');
        }
    
        $validatedData = $request->validate([
            'body' => 'required|max:256',
        ]);
    
        $comment->body = $validatedData['body'];
        $comment->save();
    
        return redirect()->back();
    }    

    public function destroy(Comment $comment)
    {
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return redirect()->route('login');
        }
    
        $comment->delete();
        return redirect()->back()->with('success', 'コメントが削除されました。');
    }

    public function report(Comment $comment)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return redirect()->back()->with('success', 'コメントが報告されました。');
    }
    
}
