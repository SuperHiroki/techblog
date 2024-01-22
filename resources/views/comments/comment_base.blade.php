{{-- comments/comment_form.blade.php --}}
<head>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<div class="row">
    <div class="col-9 col-md-10">
        <div class="d-flex align-items-center justify-content-left">
            <a href="{{ route('my-page.profile', $item->user_id) }}" class="custom-user-link rounded p-1 d-flex align-items-center justify-content-left">
                @if($item->user->icon_image)
                    <img src="{{ asset('storage/' . $item->user->icon_image) }}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                @else
                    <img src="{{ asset('images/default-icons/avatar.png')}}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                @endif
                <div class="small rounded">&#64;{{ $item->user->name }}</div>
            </a>
        </div>
        <div style="margin-left: 42px;" class="" id="commentBodyText{{ $item->id }}" style="white-space: pre-wrap;">{{ $item->body }}</div>
    </div>
    <div class="col-3 col-md-2 d-flex align-items-center justify-content-center">
        <!-- いいねボタン -->
        <div class="custom-three-point rounded">
            <!--同期通信-->
            <div style="display:none">
                @if ($item->likedByAuthUser)
                    <form id="unlike-form-{{ $item->id }}" action="{{ route('unlike-comment', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <img src="{{ asset('images/like_bookmark_archive/like.png') }}" onclick="document.getElementById('unlike-form-{{ $item->id }}').submit();" alt="like" style="cursor: pointer; width: 15px; height: auto;">
                @else
                    <form id="like-form-{{ $item->id }}" action="{{ route('like-comment', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <img src="{{ asset('images/like_bookmark_archive/unlike.png') }}" onclick="document.getElementById('like-form-{{ $item->id }}').submit();" alt="unlike" style="cursor: pointer; width: 15px; height: auto;">
                @endif
            </div>
            <!--非同期通信-->
            <div>
                <img class="icon-to-add-function" id="like-icon-of-item-{{ $item->id }}" data-item-id="{{ $item->id }}" data-current-type="like" src="{{ asset('images/like_bookmark_archive/like.png') }}" alt="like" style="cursor: pointer; width: 15px; height: auto; display: {{$item->likedByAuthUser ? 'block' : 'none'}};">
                <img class="icon-to-add-function" id="unlike-icon-of-item-{{ $item->id }}" data-item-id="{{ $item->id }}" data-current-type="unlike" src="{{ asset('images/like_bookmark_archive/unlike.png') }}" alt="unlike" style="cursor: pointer; width: 15px; height: auto; display: {{$item->likedByAuthUser ? 'none' : 'block'}};">
            </div>
        </div>
        <!-- いいね数 -->
        <div class="px-1" id="like-count-of-item-{{$item->id}}">
            {{$item->likesCount}}
        </div>
        <!-- 三点リーダーメニュー -->
        <div>
            <button class="btn btn-lg custom-three-point rounded lg" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                &#8942;
            </button>
            @if (Auth::id() === $item->user_id)
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                    <li><a class="dropdown-item" href="#" onclick="showEditForm({{ $item->id }}); return false;">編集</a></li>
                    <li>
                        <form action="{{ route('comments.destroy', $item) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item">削除</button>
                        </form>
                    </li>
                </ul>
            @else
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                    <li>
                        <form action="{{ route('comments.report', $item) }}" method="post" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="dropdown-item">報告</button>
                        </form>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>

@if (Auth::id() === $item->user_id)
    <div id="editForm{{ $item->id }}" style="display:none">
        <form action="{{ route('comments.update', $item) }}" method="post">
            @csrf
            @method('PATCH')
            <textarea name="body" class="form-control" rows="3">{{ $item->body }}</textarea>
            <button type="submit" class="btn btn-primary mt-2">更新</button>
            <button type="button" onclick="showEditForm({{ $item->id }})" class="btn btn-secondary mt-2">キャンセル</button>
        </form>
    </div>
@endif

<script>
//jsで制御する
function showEditForm(commentId) {
    //編集フォーム
    var editForm = document.getElementById('editForm' + commentId);
    //普通に表示されるテキストやボタン
    var commentBodyText = document.getElementById('commentBodyText' + commentId);
    if (editForm.style.display === "none") {
        editForm.style.display = "block";
        commentBodyText.style.display = "none";
    } else {
        editForm.style.display = "none";
        commentBodyText.style.display = "block";
    }
}
</script>