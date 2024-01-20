<div class="row justify-content-center">
    <div class="col-lg-6">
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
        <div class="col-md-12 mb-3">
            <div class="card shadow">
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
                        <div class="m-1">
                            <!---------------------------------------------------------------------------------------->
                            <!--同期処理-->
                            <div style="display:none">
                                @if($author->is_followed)
                                    <form action="{{ route('unfollow-author', $author->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">フォロー解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow-author', $author->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">フォロー</button>
                                    </form>
                                @endif
                            </div>
                            <!--非同期処理-->
                            <div>
                                <button style="display:{{$author->is_followed ? 'block' : 'none'}}"
                                        data-author-id="{{$author->id}}" 
                                        data-current-type = "follow"
                                        class="button-to-add-func btn btn-danger btn-sm">フォロー解除</button>
                                <button style="display:{{$author->is_followed ? 'none' : 'block'}}"
                                        data-author-id="{{$author->id}}" 
                                        data-current-type = "unfollow"
                                        class="button-to-add-func btn btn-success btn-sm">フォロー</button>
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
</script>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--非同期でリクエストを送るための補助メソッド-->
@include('js.common-async-fetch-js')
<script>
//非同期でフォロー（アンフォロー）をつけるために設定
document.addEventListener('DOMContentLoaded', function () {
    setEventToButtons();
})

//フォローとアンフォローの関数をボタンにセット
function setEventToButtons(){
    document.querySelectorAll('.button-to-add-func').forEach(item => {
        item.addEventListener('click', async function() {
            try{
                //apiトークンの取得
                const apiToken = getApiToken();
                //著者ID
                const authorId = this.dataset.authorId;
                //リクエストの種類（フォロー、アンフォロー）
                const currentType = this.dataset.currentType;
                const targetType = revserseType(currentType);
                //メソッド
                const method = getMethod(targetType);
                //URL
                const url = `${baseUrl}/api/${targetType}-author/${authorId}`;
                //fetch
                const jsonData = await fetchApi(url, method, apiToken); 
                //UIの切り替え。
                toggleCheckedAuthor(authorId, currentType, targetType);
                //フラッシュメッセージ
                showFlush("success", jsonData.message);
            }catch (error) {
                showFlush("error", error);
                console.error('Error:', error);
            }
        });
    });
}

//follow, unfollowのボタン表示を変更する。
function toggleCheckedAuthor(authorId, currentType, targetType) {
    const buttons = document.querySelectorAll('.button-to-add-func[data-author-id="' + authorId + '"]');
    toggleChecked(buttons, currentType, targetType);
}
</script>
