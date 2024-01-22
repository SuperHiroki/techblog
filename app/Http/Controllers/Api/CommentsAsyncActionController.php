<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Comment;
use App\Models\CommentLike;

use App\Http\Controllers\Controller;

class CommentsAsyncActionController extends Controller
{
    //返信一覧
    public function replies(Request $request, Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
        }
    
        $replies = $comment->replies()->paginate(4);
    
        $repliesHtml = view('comments.replies_template', compact('replies'))->render();
    
        return response()->json(['html' => $repliesHtml, 'message' => "次のコメントに対する返信一覧を取得しました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }

    //コメントを追加する。
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
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
    
        if($comment->parent_id == null){
            $commentHtml = view('comments.comment_template', ['comment' => $comment])->render();
        }else{
            $commentHtml = view('comments.reply_template', ['reply' => $comment])->render();
        }
    
        return response()->json(['html' => $commentHtml, 'message' => "次のコメントを追加しました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }

    //コメントを編集する。
    public function update(Request $request, Comment $comment)
    {
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
        }
    
        $validatedData = $request->validate([
            'body' => 'required|max:256',
        ]);
    
        $comment->body = $validatedData['body'];
        $comment->save();
    
        $commentHtml = view('comments.comment_template', ['comment' => $comment])->render();
    
        return response()->json(['html' => $commentHtml, 'message' => "次のようにコメントを更新しました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }    

    //コメントを削除する。
    public function destroy(Comment $comment)
    {
Log::info('DDDDDDDDDDDDD');

        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
        }
    
        $comment->delete();

        return response()->json(['message' => "次のコメントが削除されました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }

    //コメントを報告する。
    public function report(Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
        }

        return response()->json(['message' =>  "次のコメントが報告されました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }
    
    //コメントにいいねする。
    public function like(Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'ログインが必要です。'], 401);
        }

        // 既にいいねしていないかチェック
        if (!$comment->likes()->where('user_id', Auth::id())->exists()) {
            $like = new CommentLike();
            $like->comment_id = $comment->id;
            $like->user_id = Auth::id();
            $like->save();
        }

        return response()->json(['message' =>  "次のコメントにいいねしました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }

    //コメントのいいねを外す。
    public function unlike(Comment $comment)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // いいねを取り消す
        $comment->likes()->where('user_id', Auth::id())->delete();

        return response()->json(['message' =>  "次のコメントへのいいねを削除しました。\n{$comment->user->name}さんのコメント: \n{$comment->body}"]);
    }
}
