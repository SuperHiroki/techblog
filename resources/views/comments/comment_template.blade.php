<div id="area-comment-{{ $comment->id }}">
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
                            <div>
                                <a class="btn btn-link text-info custom-link show-replies-to-comment" data-comment-id="{{ $comment->id }}" data-bs-toggle="collapse" href="#replies{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replies{{ $comment->id }}">
                                    <div class="d-flex align-items-center justify-content-left">
                                        <div class="p-2">
                                            <img id="triangle-to-comment-{{ $comment->id }}" src="{{asset('images/icons/triangle.png')}}" style="width: 15px; height: 15px;" />
                                        </div>
                                        {{ $comment->repliesCount}}件の返信
                                    </div>
                                </a>
                            </div>
                            {{-- 返信一覧 --}}
                            <div class="collapse" id="replies{{ $comment->id }}">
                                <!--ここの部分は非同期で取得することで、ここの部分でもページネーションを実装する。-->
                                <div id="replies-container-to-comment-{{ $comment->id }}" class="replies-to-comment" data-comment-id="{{ $comment->id }}"></div>
                                <!--さらに返信を表示するためのボタン-->
                                <div class="d-flex align-items-center justify-content-left m-1 p-1" style="cursor:pointer">
                                    <div class="rounded custom-link" id="show-more-replies-to-comment-{{ $comment->id }}">さらに返信を表示</div>
                                </div>
                                <!--自分のコメントの追加領域-->
                                <div id="my-reply-added-field-to-comment-{{ $comment->id }}"></div>
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
                            <textarea id="textarea-reply-{{$comment->id}}" name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary m-2 button-to-add-reply" data-comment-id="{{ $comment->id }}">返信を追加</button>
                        <button type="button" class="btn btn-secondary m-2" onclick="toggleReplyForm({{ $comment->id }})">キャンセル</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>