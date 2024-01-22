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
                <button type="submit" class="btn btn-primary m-2" id="button-to-add-comment">コメント追加</button>
                <button type="button" class="btn btn-secondary m-2" onclick="toggleAddCommentForm()">キャンセル</button>
            </form>
        </div>
    </div>
</div>

{{-- コメント一覧 --}}
<div id="comments-container">
    @foreach ($comments as $comment)
        @include('comments.comment_template', ['comment' => $comment])
    @endforeach

    <div id="commentAsyncAddedField"></div><!--コメントを非同期的に追加する場所-->

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
//ページ読み込み時に発火する。
document.addEventListener('DOMContentLoaded', function () {
    //コメント追加
    setEventToAddComment();
    //返信追加
    setEventToAddReply();
    //返信一覧を取得する
    setEventToShowReplies();
});

/////////////////////////////////////////////////////////////
//コメント追加
function setEventToAddComment(){
    document.getElementById('button-to-add-comment').addEventListener('click', async function () {
        event.preventDefault();
        try {
            //apiトークンの取得
            const apiToken = getApiToken();
            //URLなど
            const method = "POST"
            const url = `${baseUrl}/api/comments`;
            const body = {"body": document.getElementById("commentBodyInput").value};
            //fetch
            const jsonData = await fetchApi(url, method, apiToken, body); 
            //追加されたコメントを埋め込む
            document.getElementById('commentAsyncAddedField').insertAdjacentHTML('beforeend', jsonData.html);
            //フラッシュメッセージ
            showFlush("success", jsonData.message);
        } catch (error) {
            showFlush("error", error);
            console.error('Error:', error);
        }
    });
}

/////////////////////////////////////////////////////////////
//コメント追加
function setEventToAddReply() {
    console.log('UUUUUU');
    document.querySelectorAll('.button-to-add-reply').forEach(item => {
        item.addEventListener('click', async function (event) {
            event.preventDefault();
            try {
                // コメントIdの取得
                const commentId = item.getAttribute('data-comment-id');
                console.log(commentId);
                // apiトークンの取得
                const apiToken = getApiToken();
                // URLなど
                const method = "POST";
                const url = `${baseUrl}/api/comments`;
                const body = {"body": document.getElementById(`textarea-reply-${commentId}`).value, "parent_id": commentId};
                console.log(body);
                // fetch
                const jsonData = await fetchApi(url, method, apiToken, body);
                // 追加されたコメントを埋め込む
                document.getElementById(`my-reply-added-field-to-comment-${commentId}`).insertAdjacentHTML('beforeend', jsonData.html);
                // フラッシュメッセージ
                showFlush("success", jsonData.message);
            } catch (error) {
                showFlush("error", error);
                console.error('Error:', error);
            }
        });
    });
}

/////////////////////////////////////////////////////////////
//返信一覧を取得する
function setEventToShowReplies(){
    document.querySelectorAll('.show-replies-to-comment').forEach(item => {
        //返信一覧のリクエストの関数を定義
        const handleClick = async function (event) {
            event.preventDefault();
            try {
                //コメントIDの取得
                const commentId = item.getAttribute('data-comment-id');
                //console.log(commentId);
                //apiトークンの取得
                const apiToken = getApiToken();
                //URLなど
                const method = "GET";
                const url = `${baseUrl}/api/comments/${commentId}/replies`;
                //fetch
                const jsonData = await fetchApi(url, method, apiToken); 
                //追加されたコメントを埋め込む
                document.getElementById(`replies-container-to-comment-${commentId}`).innerHTML = jsonData.html;
                //フラッシュメッセージ
                showFlush("success", jsonData.message);
                //イベントを削除する。
                item.removeEventListener('click', handleClick);
                //ページネーションを設定する。
                setPaginationReplies();
            } catch (error) {
                showFlush("error", error);
                console.error('Error:', error);
            }
        }
        // rotateTriangle関数の定義
        const rotateTriangle = function() {
            const commentId = item.getAttribute('data-comment-id');
            var triangleIcon = document.getElementById(`triangle-to-comment-${commentId}`);
            if(item.getAttribute('aria-expanded') === "false"){
                triangleIcon.style.transform = "none";
            } else if(item.getAttribute('aria-expanded') === "true"){
                triangleIcon.style.transform = "rotate(180deg)";
            }
        };
        //関数を設定
        item.addEventListener('click', handleClick);
        item.addEventListener('click', rotateTriangle);
    });
}

// 初めに返信が読み込まれたときに発火する。
function setPaginationReplies () {
    document.querySelectorAll(".replies-to-comment").forEach(item => {
        const commentId = item.getAttribute('data-comment-id');
        console.log("AAAAAAAAAAAAA commentId");
        console.log(commentId);
        const lastPage = document.getElementById(`keep-replies-lastPage-to-comment-${commentId}`).textContent;
        console.log(lastPage);
        console.log("AAAAAAAAAAAAA lastPage");
        console.log(lastPage);
        paginationReplies(baseUrl, lastPage, commentId, item);
    });
};

// ページネーションのメイン処理
function paginationReplies(baseUrl, lastPage, commentId, item) {
    let currentPage = 1;
    const url = new URL(`${baseUrl}/api/comments/${commentId}/replies`);

    document.getElementById(`show-more-replies-to-comment-${commentId}`).addEventListener('click', () => {
        console.log('YYYYYYYYYYYYYYY');
        loadMoreArticles();
    });

    console.log(lastPage);

    function loadMoreArticles() {
        if (currentPage >= lastPage) {
            return;
        }

        currentPage++;
        url.searchParams.set('page', currentPage);

        console.log(url);

        const apiToken = getApiToken();

        fetch(url, {
            headers: {
                'Authorization': `Bearer ${apiToken}`, // トークンをヘッダーに追加
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                // レスポンスが ok でない場合はエラーを投げる
                return response.json().then(data => {
                    throw new Error(data.message || 'Network response was not ok.');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            const parser = new DOMParser();
            const htmlDocument = parser.parseFromString(data.html, "text/html");
            const newArticles = htmlDocument.documentElement.innerHTML; // HTMLコンテンツを取得
            item.innerHTML += newArticles; 
        })
        .catch(error => {
            console.error('Error:', error);
            showFlush("error", error);
            throw new Error(error.message || 'Something went wrong.');
        });

        if (currentPage >= lastPage) {
            document.getElementById(`show-more-replies-to-comment-${commentId}`).style.display="none";
            return;
        }
    }
}
/////////////////////////////////////////////////////////////
//報告する。


</script>
@endsection
