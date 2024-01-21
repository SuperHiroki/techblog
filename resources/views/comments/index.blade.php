{{-- comments/index.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- 説明 --}}
<div>
    <p>※このサイトで管理したい著者がいれば申請してください。著者が登録されると、その著者の記事にいいね（ブックマーク、アーカイブ）をつけることができます。</p>
</div>

{{-- コメント追加フォーム --}}
<div class="card" style="border: none;">
    <div class="card-body">
        <div id="addCommentFormButton">
            <a onclick="toggleAddCommentForm()" class="btn btn-link text-info custom-link" data-bs-toggle="collapse" href="#addCommentForm" role="button" aria-expanded="false" aria-controls="addCommentForm">コメントを追加</a>
        </div>
        <div id="addCommentForm" class="collapse">
            <form action="{{ route('comments.add') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="commentBody" class="mb-2">Your Comment :</label>
                    <textarea name="body" id="commentBody" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary m-2">コメント追加</button>
                <button type="button" class="btn btn-secondary m-2" onclick="toggleAddCommentForm()">キャンセル</button>
            </form>
        </div>
    </div>
</div>

{{-- コメント一覧 --}}
@foreach ($comments as $comment)
    <div class="card mb-3">
        <div class="card-body">

            {{-- コメント --}}
            @include('comments.comment_form', ['item' => $comment])

            {{-- 返信一覧 --}}
            @if ($comment->replies->count() > 0)
                <div style="margin-left: 40px;">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- 返信ボタン --}}
                            <div id="repliesButton{{ $comment->id }}">
                                <a onclick="toggleReplies({{ $comment->id }})" class="btn btn-link text-info mt-2 custom-link" data-bs-toggle="collapse" href="#replies{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replies{{ $comment->id }}">返信を表示</a>
                            </div>
                            {{-- 返信 --}}
                            <div class="collapse" id="replies{{ $comment->id }}">
                                @foreach ($comment->replies as $reply)
                                    <hr>
                                    <div>
                                        @include('comments.comment_form', ['item' => $reply])
                                    </div>
                                @endforeach
                                <button type="button" class="btn btn-secondary mt-2" onclick="toggleReplies({{ $comment->id }})">返信を閉じる</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 返信フォームボタン --}}
            <div style="margin-left: 40px;">
                <div id="replyFormButton{{ $comment->id }}">
                    <a onclick="toggleReplyForm({{ $comment->id }})" class="btn btn-link text-info mt-2 custom-link" data-bs-toggle="collapse" href="#replyForm{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replyForm{{ $comment->id }}">返信する</a>
                </div>
                {{-- 返信フォーム --}}
                <div class="collapse" id="replyForm{{ $comment->id }}">
                    <form action="{{ route('comments.add') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary m-2">返信を追加</button>
                        <button type="button" class="btn btn-secondary m-2" onclick="toggleReplyForm({{ $comment->id }})">キャンセル</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endforeach

<!------------------------------------------------------------------------------------------------------>
<!--Collapseによる展開-->
<script>
function toggleAddCommentForm() {
    var addCommentForm = document.getElementById('addCommentForm');
    var addCommentFormButton = document.getElementById('addCommentFormButton');
    toggleCollapse(addCommentForm, addCommentFormButton);
}

function toggleReplies(commentId) {
    var replies = document.getElementById('replies' + commentId);
    var repliesButton = document.getElementById('repliesButton' + commentId);
    toggleCollapse(replies, repliesButton);
}

function toggleReplyForm(commentId) {
    var replyForm = document.getElementById('replyForm' + commentId);
    var replyFormButton = document.getElementById('replyFormButton' + commentId);
    toggleCollapse(replyForm, replyFormButton);
}

// 共通のトグル機能
function toggleCollapse(section, button) {
    if (section.classList.contains('show')) {
        section.classList.remove('show');
        button.style.display = 'block';
        button.setAttribute('aria-expanded', 'false');
    } else {
        section.classList.add('show');
        button.style.display = 'none';
        button.setAttribute('aria-expanded', 'true');
    }
}
</script>
@endsection
