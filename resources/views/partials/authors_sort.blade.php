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
            <option value="followers">フォロワー数</option>
            <option value="trending_followers">急上昇（フォロワー数）</option>
            <option value="articles">記事数</option>
            <option value="trending_articles">急上昇（記事数）</option>
            <option value="alphabetical">ABC順</option>
        </select>

        <select id="trendingOption" style="display: none;" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
            <option value="week">一週間</option>
            <option value="month">一か月</option>
            <option value="year">一年</option>
        </select>
    </div>
</div>

@if($authors->count() > 0)
<div class="row" id="authors-container">
    @foreach ($authors as $author)
        <div class="col-md-12 mb-3" id="to-exclude-trashed-author-{{$author->id}}"><!--ゴミ箱に投げられた時表示・非表示を切り替える。-->
            <div class="card shadow">
                <div id="for-gray-overlay-{{$author->id}}" class="{{$author->trashed_by_current_user ? 'gray-overlay' : ''}}"></div><!--オーバーレイ-->
                <div class="row g-0">
                    <div class="col-md-2 d-flex align-items-center justify-content-center mx-auto" onclick="window.open('{{ $author->link }}', '_blank')" style="max-width: 300px; cursor: pointer;">
                        @if($author->thumbnail_url)
                            <img src="{{ $author->thumbnail_url }}" class="img-fluid" alt="Author Thumbnail.">
                        @else
                            <span class="text-center">Author Thumbnail.</span>
                        @endif
                    </div>
                    <div class="col-md-8 d-flex align-items-center justify-content-center" onclick="window.open('{{ $author->link }}', '_blank')" style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $author->name }}</h4>
                            <div class="d-flex justify-content-center mb-2">
                                <img src="{{ $author->favicon_url }}" style="width: 20px; height: auto; margin-right: 5px;"  alt="Author Favicon.">
                                <a href="{{ $author->link }}" target="_blank">{{ $author->link }}</a>
                            </div>
                            <div>
                                <div>
                                    @if(request()->query('period') == "week")
                                        一週間での
                                    @elseif(request()->query('period') == "month")
                                        一か月での
                                    @elseif(request()->query('period') == "year")
                                        一年での
                                    @else
                                        全期間での
                                    @endif
                                        フォロワー増加数: {{ $author->followers_count ?? 0 }}
                                </div>
                                <div>
                                    @if(request()->query('period') == "week")
                                        一週間での
                                    @elseif(request()->query('period') == "month")
                                        一か月での
                                    @elseif(request()->query('period') == "year")
                                        一年での
                                    @else
                                        全期間での
                                    @endif
                                        記事作成数: {{ $author->articles_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <div class="m-1 gap-0 d-flex align-items-center justify-content-center">
                            <!---------------------------------------------------------------------------------------->
                            <!--フォロー（アンフォロー）-->
                            <!--非同期処理-->
                            <div>
                                <button style="display:{{$author->is_followed ? 'block' : 'none'}}"
                                        data-author-id="{{$author->id}}" 
                                        data-current-type = "follow"
                                        class="button-to-add-func btn btn-danger btn-sm"
                                        onclick="onclickRunActionToAuthor(this)">フォロー解除</button>
                                <button style="display:{{$author->is_followed ? 'none' : 'block'}}"
                                        data-author-id="{{$author->id}}" 
                                        data-current-type = "unfollow"
                                        class="button-to-add-func btn btn-success btn-sm"
                                        onclick="onclickRunActionToAuthor(this)">フォロー</button>
                            </div>
                            <!---------------------------------------------------------------------------------------->
                            <!--trash-->
                            <!--非同期処理-->
                            <div class="custom-icon rounded">
                                <img style="display:{{$author->trashed_by_current_user ? 'block' : 'none'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/trash.png"
                                        class="button-to-add-func" 
                                        data-author-id="{{ $author->id }}" 
                                        data-current-type="trash"
                                        alt="trash"
                                        onclick="onclickRunActionToAuthor(this)">
                                <img style="display:{{$author->trashed_by_current_user ? 'none' : 'block'}}; cursor: pointer; width: 30px; height: auto;"
                                        src="/images/like_bookmark_archive/untrash.png"
                                        class="button-to-add-func"
                                        data-author-id="{{ $author->id }}"
                                        data-current-type="untrash"
                                        alt="untrash"
                                        onclick="onclickRunActionToAuthor(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
    pagination({{ $authors->lastPage() }}, "authors-container");
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
    "normal": ["followers", "articles", "alphabetical"],
    "trending": ["trending_followers", "trending_articles"]
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
<!--非同期でリクエストを送るための補助メソッド-->
@include('js.common-async-fetch-js')
<script>
//非同期でフォロー（アンフォロー）をつけるために設定
async function onclickRunActionToAuthor(item){
    try{
        //著者ID
        const authorId = item.dataset.authorId;
        //リクエストの種類（フォロー、アンフォロー）
        const currentType = item.dataset.currentType;
        const targetType = reverseType(currentType);
        //メソッド
        const method = getMethod(targetType);
        //URL
        const url = `${baseUrl}/api/${targetType}-author/${authorId}`;
        //fetch
        const jsonData = await fetchApi(url, method); 
        //UIの切り替え。
        toggleCheckedAuthor(authorId, currentType, targetType);
        toggleTrashOverlayAuthor(authorId, targetType);
        //フラッシュメッセージ
        showFlush("success", jsonData.message);
    }catch (error) {
        showFlush("error", error);
        console.error('Error:', error);
    }
}

//follow, unfollowのボタン表示を変更する。
function toggleCheckedAuthor(authorId, currentType, targetType) {
    const buttons = document.querySelectorAll('.button-to-add-func[data-author-id="' + authorId + '"]');
    toggleChecked(buttons, currentType, targetType);
}

//ゴミ箱に入れたらオーバーレイを適用する。
function toggleTrashOverlayAuthor(authorId, targetType) {
    const overlaySection = document.getElementById(`for-gray-overlay-${authorId}`);
    toggleTrashOverlay(overlaySection, targetType);
}
</script>
