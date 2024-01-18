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
                            <img src="{{ $author->thumbnail_url }}" class="img-fluid" alt="No Image">
                        @else
                            <span class="text-center">No Image!</span>
                        @endif
                    </div>
                    <div class="col-md-8 d-flex align-items-center justify-content-center" onclick="window.open('{{ $author->link }}', '_blank')" style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $author->name }}</h4>
                            <div class="d-flex justify-content-center mb-2">
                                <img src="{{ $author->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
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
                                        data-target-type = "unfollow"
                                        class="button-to-add-func btn btn-danger btn-sm">フォロー解除</button>
                                <button style="display:{{$author->is_followed ? 'none' : 'block'}}"
                                        data-author-id="{{$author->id}}" 
                                        data-target-type = "follow"
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

<script>
//ページネーション用
document.addEventListener('DOMContentLoaded', function () {

    let currentPage = 1;
    const lastPage = {{ $authors->lastPage() }};

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
                const newArticles = htmlDocument.getElementById("authors-container").innerHTML;
                document.getElementById("authors-container").innerHTML += newArticles;
            });
    }
});
</script>

<script>
// ページ読み込み時に適切なオプションを選択
function initializeSortOptions() {
    const urlParams = new URLSearchParams(window.location.search);
    const sort = urlParams.get('sort');//絶対に必要
    const period = urlParams.get('period');//trendingの時は絶対に必要

    document.getElementById("sortOption").value = sort;

    if (sort=='trending_followers' || sort=='trending_articles'){
        document.getElementById("trendingOption").style.display = "block";
        document.getElementById("trendingOption").value = period;
    } else if(sort=='followers' || sort=='articles' || sort=='alphabetical') {
        document.getElementById("trendingOption").style.display = "none";
    }
}

//選択肢を切り替えたとき
function updateSort() {
    const sort = document.getElementById("sortOption").value;
    const period = document.getElementById("trendingOption").value;

    if (sort=='trending_followers' || sort=='trending_articles') {
        document.getElementById("trendingOption").style.display = "block";
        location = window.location.pathname + "?sort=" + sort + "&period=" + period;
    } else if(sort=='followers' || sort=='articles' || sort=='alphabetical') {
        document.getElementById("trendingOption").style.display = "none";
        location = window.location.pathname + "?sort=" + sort;
    }
}

// ページ読み込み時に初期化
window.onload = initializeSortOptions;
</script>

<script>
//定数
const baseUrl ="https://techblog.shiroatohiro.com"
const apiToken = localStorage.getItem('apiToken');

//非同期でいいね（ブックマーク、アーカイブ）をつけるために設定
document.querySelectorAll('.button-to-add-func').forEach(item => {
    item.addEventListener('click', function() {
        //記事ID
        const authorId = this.dataset.authorId;
        //リクエストの種類（フォロー、アンフォロー）
        const targetType = this.dataset.targetType;
        //URL
        const url = `${baseUrl}/api/${targetType}-author/${authorId}`;
        //手法
        let method;
        if(targetType.startsWith('un')){
            method = 'DELETE';
        }else{
            method = 'POST';
        }

        fetch(url, {
            method: method, 
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': `Bearer ${apiToken}`,
                'Content-Type': 'application/json',
            },
            //body: JSON.stringify({ authorId: authorId })//いらないね。
        })
        .then(response => {
            const contentType = response.headers.get('Content-Type');
            if (contentType && contentType.includes('application/json')) {
                // JSONレスポンスを解析する
                return response.json().then(json => {
                    if (response.ok) {
                        return json;
                    } else {
                        throw new Error(json.message || response.statusText);
                    }
                });
            } else {
                // JSONでない場合は、直接statusTextを使用
                throw new Error(response.statusText);
            }
        })
        .then(data => {
            if (data.message) {
                toggleChecked(authorId, targetType);
                document.getElementById('flush_success').innerText = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('flush_error').innerText = error;
        });
    });
});

//follow, unfollowのボタン表示を変更する。
function toggleChecked(authorId, targetType) {
    const buttons = document.querySelectorAll('.button-to-add-func[data-author-id="' + authorId + '"]');

    buttons.forEach(function(button) {
        if(button.dataset.targetType === revserseType(targetType)){
            button.style.display = 'block'; 
        }else if (button.dataset.targetType === targetType){
            button.style.display = 'none'; 
        }
    });
}

//タイプを逆転する。
function revserseType(type){
    if(type.startsWith('un')){
        reversedType = type.substring(2);
    }else{
        reversedType = 'un' + type;
    }
    return reversedType
}
</script>