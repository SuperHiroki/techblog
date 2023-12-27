@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card text-white bg-secondary mb-4 shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center">おすすめ記事</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <select id="sortOption" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
                    <option value="likes">いいね数順</option>
                    <option value="bookmarks">ブックマーク数順</option>
                    <option value="archives">アーカイブ数順</option>
                    <option value="newest">新しい順</option>
                    <option value="trending_likes">急上昇（いいね数）</option>
                    <option value="trending_bookmarks">急上昇（ブックマーク数）</option>
                    <option value="trending_archives">急上昇（アーカイブ数）</option>
                </select>

                <select id="trendingOption" style="display: none;" onchange="sortTrending()" class="form-select form-select-lg mb-3 border border-dark">
                    <option value="week">一週間</option>
                    <option value="month">一か月</option>
                    <option value="year">一年</option>
                </select>
            </div>
        </div>

        <div class="row">
            @foreach ($articles as $article)
                <div class="col-md-12 mb-3" onclick="location.href='{{ $article->link }}'" style="cursor: pointer;">
                    <div class="card shadow">
                        <div class="row g-0">
                            <div class="col-md-2 d-flex align-items-center justify-content-center mx-auto" style="max-width: 300px;">
                                @if($article->thumbnail_url)
                                    <img src="{{ $article->thumbnail_url }}" class="img-fluid" alt="Article Image">
                                @else
                                    <span class="text-center">No Image</span>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $article->title }}</h5>
                                    <p class="card-text">{{ $article->description }}</p>
                                    <div class="d-flex justify-content-center mb-2">
                                        <img src="{{ $article->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
                                        <a href="{{ $article->link }}" target="_blank">{{ $article->link }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center justify-content-center mb-2">
                                @if($goodArticles->contains('id', $article->id))
                                    <form action="{{ route('unlike-article', $article->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">いいね解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('like-article', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">いいね</button>
                                    </form>
                                @endif
                                @if($bookmarkArticles->contains('id', $article->id))
                                    <form action="{{ route('unbookmark-article', $article->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">ブックマーク解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('bookmark-article', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">ブックマーク</button>
                                    </form>
                                @endif
                                @if($archiveArticles->contains('id', $article->id))
                                    <form action="{{ route('unarchive-article', $article->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">アーカイブ解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('archive-article', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">アーカイブ</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

<script>
    // ページ読み込み時に適切なオプションを選択
    function initializeSortOptions() {
        const urlParams = new URLSearchParams(window.location.search);
        const sort = urlParams.get('sort');
        const period = urlParams.get('period');

        if (sort === 'trending') {
            document.getElementById("trendingOption").style.display = "block";
            document.getElementById("trendingOption").value = period || 'week';
        } else {
            document.getElementById("trendingOption").style.display = "none";
        }

        if (sort) {
            document.getElementById("sortOption").value = sort;
        }
    }

    function updateSort() {
        const sort = document.getElementById("sortOption").value;
        if (sort === "trending") {
            document.getElementById("trendingOption").style.display = "block";
            sortTrending();
        } else {
            document.getElementById("trendingOption").style.display = "none";
            location = "{{ route('recommended-articles') }}?sort=" + sort;
        }
    }

    function sortTrending() {
        const period = document.getElementById("trendingOption").value;
        location = "{{ route('recommended-articles') }}?sort=trending&period=" + period;
    }

    // ページ読み込み時に初期化
    window.onload = initializeSortOptions;
</script>
@endsection
