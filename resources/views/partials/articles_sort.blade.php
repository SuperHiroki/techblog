<div class="row justify-content-center">

    <div class="col-12 col-lg-9">
        <input type="text" id="keywords" class="form-control" placeholder="キーワードを入力" style="border: 2px solid black">
        <div class="d-flex justify-content-end me-5 mt-2">
            <button onclick="keywordsSearch()" class="btn btn-primary">検索</button>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <label for="sortOption" class="m-1">ソートの方法を選択:</label>
        <select id="sortOption" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
            <option value="likes">いいね数順</option>
            <option value="bookmarks">ブックマーク数順</option>
            <option value="archives">アーカイブ数順</option>
            <option value="newest">新しい順</option>
            @if(!request()->is('my-page/*/recent-articles/*'))
                <option value="trending_likes">急上昇（いいね数）</option>
                <option value="trending_bookmarks">急上昇（ブックマーク数）</option>
                <option value="trending_archives">急上昇（アーカイブ数）</option>
            @endif
        </select>

        <select id="trendingOption" style="display: none;" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
            <option value="week">一週間</option>
            <option value="month">一か月</option>
            <option value="year">一年</option>
        </select>
    </div>
</div>

@if($articles->count() > 0)
<div class="row" id="articles-container">
    @foreach ($articles as $article)
        <div class="col-md-12 mb-3" id="to-exclude-trashed-article-{{$article->id}}">
            <div class="card shadow ">
                <div id="for-gray-overlay-{{$article->id}}" class="{{$article->trashed_by_current_user ? 'gray-overlay' : ''}}"></div><!--オーバーレイ-->
                <div class="row g-0">
                    <div class="col-md-2 d-flex align-items-center justify-content-center mx-auto" style="max-width: 300px;" onclick="window.open('{{ $article->link }}', '_blank')" style="cursor: pointer;">
                        @if($article->thumbnail_url)
                            <img src="{{ $article->thumbnail_url }}" class="img-fluid" alt="Article Thumbnail." style="cursor: pointer;">
                        @else
                            <span class="text-center">Article Thumbnail.</span>
                        @endif
                    </div>
                    <div class="col-md-8" onclick="window.open('{{ $article->link }}', '_blank')" style="cursor: pointer;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text"><strong>{{ $article->author->name }}</strong></p>
                            <p class="card-text"><small>{{ Str::limit($article->description, 100, '...') }}</small></p>
                            <div class="d-flex justify-content-center mb-2">
                                <img src="{{ $article->favicon_url }}" style="width: 20px; height: auto; margin-right: 5px;" alt="Article Favicon.">
                                <a href="{{ $article->link }}" target="_blank">{{ $article->link }}</a>
                            </div>
                            <div class="d-flex justify-content-center">
                                @if(request()->query('sort') == "likes")
                                        いいね数： {{ $article->like_users_count }}
                                @elseif(request()->query('sort') == "bookmarks")
                                        ブックマーク数： {{ $article->bookmark_users_count }}
                                @elseif(request()->query('sort') == "archives")
                                        アーカイブ数： {{ $article->archive_users_count }}
                                @elseif(request()->query('sort') == "newest")
                                        作成日： {{ $article->created_date ?? "不明" }}
                                @elseif(request()->query('sort') == "trending_likes")
                                    @if(request()->query('period') == "week")
                                        一週間でのいいね増加： {{ $article->like_users_count }}
                                    @elseif(request()->query('period') == "month")
                                        一か月でのいいね増加： {{ $article->like_users_count }}
                                    @elseif(request()->query('period') == "year")
                                        一年でのいいね増加： {{ $article->like_users_count }}
                                    @endif
                                @elseif(request()->query('sort') == "trending_bookmarks")
                                    @if(request()->query('period') == "week")
                                        一週間でのブックマーク増加： {{ $article->bookmark_users_count }}
                                    @elseif(request()->query('period') == "month")
                                        一か月でのブックマーク増加： {{ $article->bookmark_users_count }}
                                    @elseif(request()->query('period') == "year")
                                        一年でのブックマーク増加： {{ $article->bookmark_users_count }}
                                    @endif
                                @elseif(request()->query('sort') == "trending_archives")
                                    @if(request()->query('period') == "week")
                                        一週間でのアーカイブ増加： {{ $article->archive_users_count }}
                                    @elseif(request()->query('period') == "month")
                                        一か月でのアーカイブ増加： {{ $article->archive_users_count }}
                                    @elseif(request()->query('period') == "year")
                                        一年でのアーカイブ増加： {{ $article->archive_users_count }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 gap-0 d-flex align-items-center justify-content-center mb-2">
                        <!----------------------------------------------------------------------->
                        <!--いいね-->
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon rounded">
                                <img style="display:{{$article->liked_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/like.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="like"
                                        alt="like"
                                        onclick="onclickRunActionToArticle(this)">
                                <img style="display:{{$article->liked_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unlike.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unlike"
                                        alt="unlike"
                                        onclick="onclickRunActionToArticle(this)">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--ブックマーク-->
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon rounded">
                                <img style="display:{{$article->bookmarked_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/bookmark.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="bookmark"
                                        alt="bookmark"
                                        onclick="onclickRunActionToArticle(this)">
                                <img style="display:{{$article->bookmarked_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unbookmark.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unbookmark"
                                        alt="unbookmark"
                                        onclick="onclickRunActionToArticle(this)">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--アーカイブ-->
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon rounded">
                                <img style="display:{{$article->archived_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/archive.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="archive"
                                        alt="archive"
                                        onclick="onclickRunActionToArticle(this)">
                                <img style="display:{{$article->archived_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unarchive.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unarchive"
                                        alt="unarchive"
                                        onclick="onclickRunActionToArticle(this)">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--trash-->
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon rounded">
                                <img style="display:{{$article->trashed_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/trash.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="trash"
                                        alt="trash"
                                        onclick="onclickRunActionToArticle(this)">
                                <img style="display:{{$article->trashed_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/untrash.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="untrash"
                                        alt="untrash"
                                        onclick="onclickRunActionToArticle(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- ページネーション用のリンク（隠し） -->
<div id="pagination-links" class="d-none">
    {{ $articles->links() }}
</div>
@else
<div class="text-center">
    <p>記事がありません</p>
</div>
@endif

<!----------------------------------------------------------------------------------------------------------------------------->
<!--ページネーション-->
@include('js.common-pagination-js')
<script>
//ページが読み込まれたときに発火する
document.addEventListener('DOMContentLoaded', function () {
    pagination({{ $articles->lastPage() }}, "articles-container");
});
</script>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--ドロップダウンの更新-->
@include('js.common-change-sort-option')
<script>
// ページ読み込み時に初期化
document.addEventListener('DOMContentLoaded', function () {
    initializeSortOptions();
})

//定数の定義
const candidates = {
    "normal": ["likes", "bookmarks", "archives", "newest"],
    "trending": ["trending_likes", "trending_bookmarks", "trending_archives"]
};

// ページ読み込み時に適切なオプションを選択
function initializeSortOptions() {
    commonInitializeSortOptions(candidates);
}

//選択肢を切り替えたとき
function updateSort() {
    commonUpdateSort(candidates);
}

//検索をクリック
function keywordsSearch(){
    commonUpdateSort(candidates);
}
</script>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--非同期でリクエストを送る-->
@include('js.common-async-fetch-js')
<script>
//クリックの時に発火する。
async function onclickRunActionToArticle(item){
    try {
        //記事ID
        const articleId = item.dataset.articleId;
        //いいね（ブックマーク、アーカイブ）などのリクエストの種類
        const currentType = item.dataset.currentType;
        const targetType = reverseType(currentType);
        //メソッド
        const method = getMethod(targetType);
        //URL
        const url = `${baseUrl}/api/${targetType}-article/${articleId}`;
        //fetch
        const jsonData = await fetchApi(url, method); 
        //UIの切り替え。
        toggleCheckedArticle(articleId, currentType, targetType);
        toggleTrashOverlayArticle(articleId, targetType);
        //フラッシュメッセージ
        showFlush("success", jsonData.message);
    } catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

//いいね（ブックマーク、アーカイブ）の表示を変更する。
function toggleCheckedArticle(articleId, currentType, targetType) {
    //実際は一つしかないけど複数ある前提で検索される。
    const icons = document.querySelectorAll('.icon-to-add-func[data-article-id="' + articleId + '"]');
    toggleChecked(icons, currentType, targetType);
}

//ゴミ箱に入れたらオーバーレイを適用する。
function toggleTrashOverlayArticle(articleId, targetType) {
    const overlaySection = document.getElementById(`for-gray-overlay-${articleId}`);
    toggleTrashOverlay(overlaySection, targetType);
}
</script>