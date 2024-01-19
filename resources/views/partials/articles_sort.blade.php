<div class="row justify-content-center">
    <div class="col-lg-6">
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
        <div class="col-md-12 mb-3">
            <div class="card shadow ">
                <div id="for-gray-overlay-{{$article->id}}" class="{{$article->trashed_by_current_user ? 'gray-overlay' : ''}}"></div><!--オーバーレイ-->
                <div class="row g-0">
                    <div class="col-md-2 d-flex align-items-center justify-content-center mx-auto" style="max-width: 300px;" onclick="window.open('{{ $article->link }}', '_blank')" style="cursor: pointer;">
                        @if($article->thumbnail_url)
                            <img src="{{ $article->thumbnail_url }}" class="img-fluid" alt="Article Image" style="cursor: pointer;">
                        @else
                            <span class="text-center">No Image</span>
                        @endif
                    </div>
                    <div class="col-md-8" onclick="window.open('{{ $article->link }}', '_blank')" style="cursor: pointer;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text"><strong>{{ $article->author->name }}</strong></p>
                            <p class="card-text"><small>{{ Str::limit($article->description, 100, '...') }}</small></p>
                            <div class="d-flex justify-content-center mb-2">
                                <img src="{{ $article->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
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
                        <!--同期通信-->
                        <div style="display:none">
                            @if($article->liked_by_current_user)
                                <div class="custom-icon">
                                    <form id="unlike-form-{{ $article->id }}" action="{{ route('unlike-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/like.png') }}" onclick="document.getElementById('unlike-form-{{ $article->id }}').submit();" alt="like" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @else
                                <div class="custom-icon">
                                    <form id="like-form-{{ $article->id }}" action="{{ route('like-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/unlike.png') }}" onclick="document.getElementById('like-form-{{ $article->id }}').submit();" alt="unlike" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @endif
                        </div>
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon">
                                <img style="display:{{$article->liked_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/like.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="like"
                                        alt="like">
                                <img style="display:{{$article->liked_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unlike.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unlike"
                                        alt="unlike">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--ブックマーク-->
                        <!--同期通信-->
                        <div style="display:none">
                            @if($article->bookmarked_by_current_user)
                                <div class="custom-icon">
                                    <form id="unbookmark-form-{{ $article->id }}" action="{{ route('unbookmark-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/bookmark.png') }}" onclick="document.getElementById('unbookmark-form-{{ $article->id }}').submit();" alt="bookmark" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @else
                                <div class="custom-icon">
                                    <form id="bookmark-form-{{ $article->id }}" action="{{ route('bookmark-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/unbookmark.png') }}" onclick="document.getElementById('bookmark-form-{{ $article->id }}').submit();" alt="unbookmark" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @endif
                        </div>
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon">
                                <img style="display:{{$article->bookmarked_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/bookmark.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="bookmark"
                                        alt="bookmark">
                                <img style="display:{{$article->bookmarked_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unbookmark.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unbookmark"
                                        alt="unbookmark">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--アーカイブ-->
                        <!--同期通信-->
                        <div style="display:none">
                            @if($article->archived_by_current_user)
                                <div class="custom-icon">
                                    <form id="unarchive-form-{{ $article->id }}" action="{{ route('unarchive-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/archive.png') }}" onclick="document.getElementById('unarchive-form-{{ $article->id }}').submit();" alt="archive" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @else
                                <div class="custom-icon">
                                    <form id="archive-form-{{ $article->id }}" action="{{ route('archive-article', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <img src="{{ asset('images/like_bookmark_archive/unarchive.png') }}" onclick="document.getElementById('archive-form-{{ $article->id }}').submit();" alt="unarchive" style="cursor: pointer; width: 30px; height: auto;">
                                </div>
                            @endif
                        </div>
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon">
                                <img style="display:{{$article->archived_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/archive.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="archive"
                                        alt="archive">
                                <img style="display:{{$article->archived_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/unarchive.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="unarchive"
                                        alt="unarchive">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------->
                        <!--trash-->
                        <!--非同期通信-->
                        <div>
                            <div class="custom-icon">
                                <img style="display:{{$article->trashed_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/trash.png"
                                        class="icon-to-add-func" 
                                        data-article-id="{{ $article->id }}" 
                                        data-current-type="trash"
                                        alt="trash">
                                <img style="display:{{$article->trashed_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/untrash.png"
                                        class="icon-to-add-func"
                                        data-article-id="{{ $article->id }}"
                                        data-current-type="untrash"
                                        alt="untrash">
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


<script>
//ページネーション用
document.addEventListener('DOMContentLoaded', function () {

    let currentPage = 1;
    const lastPage = {{ $articles->lastPage() }};

    window.addEventListener('scroll', () => {
        if (window.scrollY + window.innerHeight + 1 >= document.documentElement.scrollHeight) {
            loadMoreArticles();
        }
    });

    function loadMoreArticles() {
        if (currentPage >= lastPage) {
            return; // 全てのページが読み込まれた場合は何もしない
        }

        console.log('YYYYYYYYYYYYYYYY 無限スクロール');

        currentPage++;
        const url = new URL(window.location.href);
        url.searchParams.set('page', currentPage);

        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const htmlDocument = parser.parseFromString(data, "text/html");
                const newArticles = htmlDocument.getElementById("articles-container").innerHTML;
                document.getElementById("articles-container").innerHTML += newArticles;
            });
    }
});
</script>

<script>
// ページ読み込み時に適切なオプションを選択
function initializeSortOptions() {
    const urlParams = new URLSearchParams(window.location.search);
    const sort = urlParams.get('sort');
    const period = urlParams.get('period');

    document.getElementById("sortOption").value = sort;

    if (sort.startsWith('trending')){
        document.getElementById("trendingOption").style.display = "block";
        document.getElementById("trendingOption").value = period;
    } else {
        document.getElementById("trendingOption").style.display = "none";
    }
}

//ドロップダウンの選択の変更をするたびに発火する
function updateSort() {
    const sort = document.getElementById("sortOption").value;
    const period = document.getElementById("trendingOption").value;
    if (sort.startsWith('trending')) {
        document.getElementById("trendingOption").style.display = "block";
        location = window.location.pathname + "?sort=" + sort + "&period=" + period;
    } else {
        document.getElementById("trendingOption").style.display = "none";
        location = window.location.pathname + "?sort=" + sort;
    }
}

// ページ読み込み時に初期化
window.onload = initializeSortOptions;
</script>


<!--非同期でいいね（ブックマーク、アーカイブ）をつけるための補助メソッド-->
<script src="{{ asset('js/common-async-fetch.js') }}"></script>
<script>
//非同期でいいね（ブックマーク、アーカイブ）をつけるために設定
document.querySelectorAll('.icon-to-add-func').forEach(item => {
    item.addEventListener('click', async function () {
        try {
            //apiトークンの取得
            const apiToken = getApiToken();
            //記事ID
            const articleId = this.dataset.articleId;
            //いいね（ブックマーク、アーカイブ）などのリクエストの種類
            const currentType = this.dataset.currentType;
            const resultType = revserseType(currentType);
            //メソッド
            const method = getMethod(resultType);
            //URL
            const url = `${baseUrl}/api/${resultType}-article/${articleId}`;
            //fetch
            const jsonData = await fetchApi(url, method, apiToken); 
            //UIの切り替え。
            toggleCheckedArticle(articleId, currentType, resultType);
            toggleTrashOverlayArticle(articleId, currentType, resultType);
            //フラッシュメッセージ
            document.getElementById('flush_success').innerText = jsonData.message;
        } catch (error) {
            document.getElementById('flush_error').innerText = error;
            console.error('Error:', error);
        }
    });
});

//いいね（ブックマーク、アーカイブ）の表示を変更する。
function toggleCheckedArticle(articleId, currentType, resultType) {
    const icons = document.querySelectorAll('.icon-to-add-func[data-article-id="' + articleId + '"]');

    icons.forEach(function(icon) {
        if(icon.dataset.currentType === resultType){
            icon.style.display = 'block'; 
        }else if (icon.dataset.currentType === currentType){
            icon.style.display = 'none'; 
        }
    });
}

//ゴミ箱に入れたらオーバーレイを適用する。
function toggleTrashOverlayArticle(articleId, currentType, resultType) {
    const overlaySection = document.getElementById(`for-gray-overlay-${articleId}`);

    if(currentType == 'trash'){
        overlaySection.classList.remove('gray-overlay');
    }else if(currentType == 'untrash'){
        overlaySection.classList.add('gray-overlay');
    }
}
</script>