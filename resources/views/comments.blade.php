{{-- comments.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card text-white bg-secondary mb-4 shadow">
                <div class="card-body">
                    <h1 class="card-title text-center">コメント</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <form action="{{ route('comments.add') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="commentBody">Your Comment:</label>
                <textarea name="body" id="commentBody" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">コメント追加</button>
        </form>
    </div>

    @foreach ($comments as $comment)
        <div class="card mb-3">
            <div class="card-body">

                <p class="card-title small">&#64;{{ $comment->user->name }}</p>
                <p class="card-text" id="commentBodyText{{ $comment->id }}">{{ $comment->body }}</p>

                @if (Auth::id() === $comment->user_id)
                    {{-- 編集フォーム --}}
                    <div id="editForm{{ $comment->id }}" style="display:none">
                        <form action="{{ route('comments.update', $comment) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <textarea name="body" class="form-control" rows="3">{{ $comment->body }}</textarea>
                            <button type="submit" class="btn btn-primary mt-2">更新</button>
                            <button type="button" onclick="showEditForm({{ $comment->id }})" class="btn btn-secondary mt-2">キャンセル</button>
                        </form>
                    </div>
                    {{-- 編集と削除のボタン --}}
                    <div id="editDeleteButton{{ $comment->id }}">
                        <button onclick="showEditForm({{ $comment->id }})" class="btn btn-primary">編集</button>
                        <form action="{{ route('comments.destroy', $comment) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </div>
                @endif

                @if ($comment->replies->count() > 0)
                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#reply{{ $comment->id }}" aria-expanded="false" aria-controls="reply{{ $comment->id }}">
                        Replies
                    </button>
                    <div class="collapse" id="reply{{ $comment->id }}">
                        @foreach ($comment->replies as $reply)
                            <div class="card card-body mt-2">
                                <p class="card-title small">&#64;{{ $reply->user->name }}</p>
                                <p id="commentBodyText{{ $reply->id }}">{{ $reply->body }}</p>

                                @if (Auth::id() === $reply->user_id)
                                    {{-- 返信の編集フォーム --}}
                                    <div id="editForm{{ $reply->id }}" style="display:none">
                                        <form action="{{ route('comments.update', $reply) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="body" class="form-control" rows="3">{{ $reply->body }}</textarea>
                                            <button type="submit" class="btn btn-primary mt-2">更新</button>
                                            <button type="button" onclick="showEditForm({{ $reply->id }})" class="btn btn-secondary mt-2">キャンセル</button>
                                        </form>
                                    </div>
                                    {{-- 編集と削除のボタン --}}
                                    <div id="editDeleteButton{{ $comment->id }}">
                                        <button onclick="showEditForm({{ $reply->id }})" class="btn btn-primary">編集</button>
                                        <form action="{{ route('comments.destroy', $reply) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">削除</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    @endforeach
</div>

{{-- JavaScriptを追加 --}}
<script>
    function showEditForm(commentId) {
        //編集に使う
        var editForm = document.getElementById('editForm' + commentId);
        //普通に表示される
        var commentBodyText = document.getElementById('commentBodyText' + commentId);
        var editDeleteButton = document.getElementById('editDeleteButton' + commentId);
        if (editForm.style.display === "none") {
            editForm.style.display = "block";
            commentBodyText.style.display = "none";
            editDeleteButton.style.display = "none";
        } else {
            editForm.style.display = "none";
            commentBodyText.style.display = "block";
            editDeleteButton.style.display = "block";
        }
    }
</script>
@endsection
