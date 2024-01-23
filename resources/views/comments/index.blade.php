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
                    <label for="commentBodyInput" class="mb-2">Your Comment :</label>
                    <textarea name="body" id="commentBodyInput" class="form-control" rows="3" required></textarea>
                </div>
                <button type="button" class="btn btn-primary m-2" id="button-to-add-comment" onclick="onclickAddComment()">コメント追加</button>
                <button type="button" class="btn btn-secondary m-2" onclick="toggleAddCommentForm()">キャンセル</button>
            </form>
        </div>
    </div>
</div>

{{-- コメント一覧 --}}
<div id="comments-container">
     <!--コメント一覧を同期的に表示する-->
    @foreach ($comments as $comment)
        @include('comments.comment_template', ['comment' => $comment])
    @endforeach

    <!--コメントを非同期的に追加する場所-->
    <div id="commentAsyncAddedField"></div>

</div>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--Collapseによる展開-->
<script>
//コメント追加フォームを展開
function toggleAddCommentForm() {
    var addCommentForm = document.getElementById('addCommentForm');
    var addCommentFormButton = document.getElementById('addCommentFormButton');
    toggleCollapse(addCommentForm, addCommentFormButton);
}

//返信フォームを展開
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

<!----------------------------------------------------------------------------------------------------------------------------->
<!--ページネーション-->
@include('js.common-pagination-js')
<script>
//ページが読み込まれたときに発火する
document.addEventListener('DOMContentLoaded', function () {
    pagination({{ $comments->lastPage() }}, "comments-container");
});
</script>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--非同期処理によるコメントのアクション-->
@include('js.common-async-fetch-js')
<script>
/////////////////////////////////////////////////////////////
//コメント追加
async function onclickAddComment() {
    try {
        //URLなど
        const method = "POST"
        const url = `${baseUrl}/api/comments`;
        const body = {"body": document.getElementById("commentBodyInput").value};
        //fetch
        const jsonData = await fetchApi(url, method, body); 
        //追加されたコメントを埋め込む
        document.getElementById('commentAsyncAddedField').insertAdjacentHTML('beforeend', jsonData.html);
        toggleAddCommentForm();
        //フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

/////////////////////////////////////////////////////////////
//返信追加
async function onclickAddReply(parentId) {
    try {
        // URLなど
        const method = "POST";
        const url = `${baseUrl}/api/comments`;
        const body = {"body": document.getElementById(`textarea-reply-${parentId}`).value, "parent_id": parentId};
        // fetch
        const jsonData = await fetchApi(url, method, body);
        // 追加されたコメントを埋め込む
        document.getElementById(`my-reply-added-field-to-comment-${parentId}`).insertAdjacentHTML('beforeend', jsonData.html);
        // フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}


/////////////////////////////////////////////////////////////
//コメント更新
async function onclickUpdateItem(itemId) {
    try {
        // URLなど
        const method = "PATCH";
        const url = `${baseUrl}/api/comments/${itemId}`;
        const body = { "body": document.getElementById(`update-textarea-item-${itemId}`).value };
        // fetch
        const jsonData = await fetchApi(url, method, body);
        // 追加されたコメントを埋め込む
        updateItemByReplacing(jsonData, itemId);
        // フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}


//itemの中身を入れ替え
function updateItemByReplacing(jsonData, commentId){
    const parser = new DOMParser();
    const htmlDocument = parser.parseFromString(jsonData.html, "text/html");
    const content = htmlDocument.getElementById(`area-item-${commentId}`).innerHTML;
    document.getElementById(`area-item-${commentId}`).innerHTML = content;
}

/////////////////////////////////////////////////////////////
//コメント削除
async function onclickDeleteComment(item) {
    try {
        // コメントIdの取得
        const commentId = item.getAttribute('data-item-id');
        const parentId = item.getAttribute('data-parent-id');
        // URLなど
        const method = "DELETE";
        const url = `${baseUrl}/api/comments/${commentId}`;
        // fetch
        const jsonData = await fetchApi(url, method);
        //コメントを削除する
        deleteCommentHtml(parentId, commentId);
        // フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

//コメントのHTMLを削除する。
function deleteCommentHtml(parentId, commentId){
    if (parentId === null || parentId === ''){
        document.getElementById(`area-comment-${commentId}`).style.display="none";
    }else{
        document.getElementById(`area-item-${commentId}`).style.display="none";
    }
}

/////////////////////////////////////////////////////////////
//コメント報告
async function onclickReportComment(itemId) {
    try {
        // apiトークンの取得
        const apiToken = getApiToken();
        // URLなど
        const method = "POST";
        const url = `${baseUrl}/api/comments/${itemId}/report`;
        // fetch
        const jsonData = await fetchApi(url, method, apiToken);
        // フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

/////////////////////////////////////////////////////////////
//いいねをつけるために
async function onclickLike(item) {
    try {
        const itemId = item.dataset.itemId;
        //いいね（ブックマーク、アーカイブ）などのリクエストの種類
        const currentType = item.dataset.currentType;
        const targetType = reverseType(currentType);
        //メソッド
        const method = getMethod(targetType);
        //URL
        const url = `${baseUrl}/api/comments/${itemId}/like`;
        //fetch
        const jsonData = await fetchApi(url, method); 
        //UIの切り替え。
        toggleCheckedItem(itemId, currentType, targetType);
        const likeElement = document.getElementById(`like-count-of-item-${itemId}`);
        changeLikeCountHtml(likeElement, targetType);
        //フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

//いいねの表示を変更する。
function toggleCheckedItem(itemId, currentType, targetType) {
    //実際は一つしかないけど複数ある前提で検索される。
    const icons = document.querySelectorAll('.icon-to-add-function[data-item-id="' + itemId + '"]');
    toggleChecked(icons, currentType, targetType);
}

//いいねのカウントの表示を変更する
function changeLikeCountHtml(element, targetType) {
    if (targetType == "like") {
        element.textContent = parseInt(element.textContent, 10) + 1;
    } else if (targetType == "unlike") {
        element.textContent = parseInt(element.textContent, 10) - 1;
    }
}

/////////////////////////////////////////////////////////////
//返信一覧を取得するためのイベントをセットする。
function onclickShowReplies(buttonElement){
    handleGetReplies(buttonElement);
    rotateTriangleClick(buttonElement);
}

async function handleGetReplies(item) {
    try {
        const parentId = item.getAttribute('data-comment-id');
        const method = "GET";
        const url = `${baseUrl}/api/comments/${parentId}/replies`;
        const jsonData = await fetchApi(url, method); 
        document.getElementById(`replies-container-to-comment-${parentId}`).innerHTML = jsonData.html;
        showFlush("success", jsonData.message);
        // ページネーションの開始
        setPaginationReplies(parentId);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

//三角形を回転させる
function rotateTriangleClick(item) {
    const parentId = item.getAttribute('data-comment-id');
    var triangleIcon = document.getElementById(`triangle-to-comment-${parentId}`);
    if(item.getAttribute('aria-expanded') === "false"){
        triangleIcon.style.transform = "none";
    } else if(item.getAttribute('aria-expanded') === "true"){
        triangleIcon.style.transform = "rotate(180deg)";
    }
}

// ページネーションのメイン処理
function setPaginationReplies(parentId) {
    const htmlAddedArea = document.getElementById(`replies-container-to-comment-${parentId}`);
    const lastPage = parseInt(document.getElementById(`keep-replies-lastPage-to-comment-${parentId}`).textContent, 10);
    const url = new URL(`${baseUrl}/api/comments/${parentId}/replies`);

    let currentPage = 1;

    document.getElementById(`show-more-replies-to-comment-${parentId}`).addEventListener('click', async () => {
        try {
            const jsonData = await loadMoreItems(url, currentPage, lastPage);
            appendHtml(htmlAddedArea, jsonData);
            currentPage++;
            if (currentPage >= lastPage) {
                document.getElementById(`show-more-replies-to-comment-${parentId}`).style.display = "none";
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
}

//ロードする。
async function loadMoreItems(url, currentPage, lastPage) {
    if (currentPage >= lastPage) {
        return;
    }

    url.searchParams.set('page', currentPage + 1);
    const jsonData = await fetchApi(url, "GET");
    return jsonData;
}

//html要素を最後の部分に埋め込む。
function appendHtml(htmlAddedArea, jsonData) {
    const parser = new DOMParser();
    const htmlDocument = parser.parseFromString(jsonData.html, "text/html");
    const newArticles = htmlDocument.documentElement.innerHTML;
    htmlAddedArea.innerHTML += newArticles;
}
</script>
@endsection
