{{-- comments/comment_form.blade.php --}}

<!--itemIdを埋め込んで検索できるようにする-->
<input type="hidden" id="getItemId" name="getItemId" value="{{$item->id}}" />

<div id="area-item-{{$item->id}}">
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
            <div>
                <!--非同期通信-->
                <div onclick="onclickLike(this)" class="icon-to-add-function custom-icon rounded p-2" id="like-icon-of-item-{{ $item->id }}" data-item-id="{{ $item->id }}" data-current-type="like" style="cursor: pointer; display: {{$item->likedByAuthUser ? 'block' : 'none'}};">
                    <img src="{{ asset('images/like_bookmark_archive/like.png') }}" alt="like" style="width: 15px; height: auto;">
                </div>
                <div onclick="onclickLike(this)" class="icon-to-add-function custom-icon rounded p-2" id="unlike-icon-of-item-{{ $item->id }}" data-item-id="{{ $item->id }}" data-current-type="unlike" style="cursor: pointer; display: {{$item->likedByAuthUser ? 'none' : 'block'}};">
                    <img src="{{ asset('images/like_bookmark_archive/unlike.png') }}" alt="unlike" style="width: 15px; height: auto;">
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
                            <button type="button" id="button-to-delete-item-{{ $item->id }}" class="dropdown-item add-func-to-delete-item" data-item-id="{{ $item->id }}" data-parent-id="{{ $item->parent_id }}" onclick="onclickDeleteComment(this)">削除</button>
                        </li>
                    </ul>
                @else
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                        <li>
                            <button type="button" id="report-button-to-item-{{ $item->id }}" class="dropdown-item add-func-to-report-item" data-item-id="{{ $item->id }}" onclick="onclickReportComment({{ $item->id }})">報告</button>
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
                <textarea name="body" class="form-control" rows="3" id="update-textarea-item-{{$item->id}}">{{ $item->body }}</textarea>
                <button type="button" id="button-to-update-item-{{ $item->id }}" class="btn btn-primary mt-2 add-func-to-update-comment" data-item-id="{{$item->id}}" onclick="onclickUpdateItem({{ $item->id }})">更新</button>
                <button type="button" onclick="showEditForm({{ $item->id }})" class="btn btn-secondary mt-2">キャンセル</button>
            </form>
        </div>
    @endif
</div>

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