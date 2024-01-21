<div class="card mb-3">
    <div class="card-body">

        {{-- コメント --}}
        @include('comments.comment_base', ['item' => $comment])

        {{-- 返信一覧 --}}
        @if ($comment->replies->count() > 0)
            <div style="margin-left: 40px;">
                <div class="row">
                    <div class="col-md-12">
                        {{-- 返信表示ボタン --}}
                        <div id="repliesButton{{ $comment->id }}">
                            <a onclick="toggleReplies({{ $comment->id }})" class="btn btn-link text-info custom-link show-replies-to-comment" data-comment-id="{{ $comment->id }}" data-bs-toggle="collapse" href="#replies{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replies{{ $comment->id }}">{{ $comment->repliesCount}}件の返信</a>
                        </div>
                        {{-- 返信一覧 --}}
                        <div class="collapse" id="replies{{ $comment->id }}">
                            <div class="d-flex align-items-center justify-content-left">
                                <div class="custom-cross rounded p-2" style="cursor: pointer;">
                                    <img onclick="toggleReplies({{ $comment->id }})" src="{{asset('images/icons/cross.png')}}" style="width: 20px; height: 20px;" />
                                </div>
                            </div>
                            <!--ここの部分は非同期で取得することで、ここの部分でもページネーションを実装する。-->
                            <div id="replies-container-to-comment-{{ $comment->id }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 返信フォームボタン --}}
        <div style="margin-left: 40px;">
            <div id="replyFormButton{{ $comment->id }}">
                <a onclick="toggleReplyForm({{ $comment->id }})" class="btn btn-link text-info custom-link" data-bs-toggle="collapse" href="#replyForm{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replyForm{{ $comment->id }}">返信する</a>
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