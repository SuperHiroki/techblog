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
        $comments = Comment::with(['replies', 'likes'])->whereNull('parent_id')->get();
    
        $comments->each(function ($comment) {
            $this->addLikeDetails($comment);
        });
    
        return view('comments.index', compact('comments'));
    }
    
    //いいね数、現在のユーザからいいねをもらっているかを取得する再帰的処理。
    private function addLikeDetails($comment)
    {
        $comment->likedByAuthUser = $comment->likes->contains('user_id', Auth::id());
        $comment->likesCount = $comment->likes->count();
    
        // 返信（replies）に対しても同様の処理を行う
        $comment->replies->each(function ($reply) {
            $this->addLikeDetails($reply);
        });
    }

    //コメントを追加する。
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

        return redirect()->back()->with('success', "以下のコメントが追加されました。\n{$comment->user->name}さんのコメント\n{$comment->body}");;
    }

    //コメントを編集する。
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
    
        return redirect()->back()->with('success', "以下のようにコメントが編集されました。\n{$comment->user->name}さんのコメント\n{$comment->body}");
    }    

    //コメントを削除する。
    public function destroy(Comment $comment)
    {
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return redirect()->route('login');
        }
    
        $comment->delete();
        return redirect()->back()->with('success', "以下のコメントが削除されました。\n{$comment->user->name}さんのコメント\n{$comment->body}");
    }

    //コメントを報告する。
    public function report(Comment $comment)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return redirect()->back()->with('success', "以下のコメントが報告されました。\n{$comment->user->name}さんのコメント\n{$comment->body}");
    }
    
    //コメントにいいねする。
    public function like(Comment $comment)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 既にいいねしていないかチェック
        if (!$comment->likes()->where('user_id', Auth::id())->exists()) {
            $like = new CommentLike();
            $like->comment_id = $comment->id;
            $like->user_id = Auth::id();
            $like->save();
        }

        return redirect()->back()->with('success', "以下のコメントにいいねしました。\n{$comment->user->name}さんのコメント\n{$comment->body}");
    }

    //コメントのいいねを外す。
    public function unlike(Comment $comment)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // いいねを取り消す
        $comment->likes()->where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', "以下のコメントへのいいねを削除しました。\n{$comment->user->name}さんのコメント\n{$comment->body}");
    }
}
