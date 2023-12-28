@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card text-white bg-secondary mb-4 shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center">おすすめ著者</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <select id="sortOption" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
                    <option value="followers">フォロワー数</option>
                    <option value="trending">急上昇</option>
                    <option value="alphabetical">ABC順</option>
                </select>

                <select id="trendingOption" style="display: none;" onchange="updateSort()" class="form-select form-select-lg mb-3 border border-dark">
                    <option value="week">一週間</option>
                    <option value="month">一か月</option>
                    <option value="year">一年</option>
                </select>
            </div>
        </div>

        <div class="row">
            @foreach ($authors as $author)
                <div class="col-md-12 mb-3" onclick="location.href='{{ $author->link }}'" style="cursor: pointer;">
                    <div class="card shadow">
                        <div class="row g-0">
                            <div class="col-md-2 d-flex align-items-center justify-content-center mx-auto" style="max-width: 300px;">
                                @if($author->thumbnail_url)
                                    <img src="{{ $author->thumbnail_url }}" class="img-fluid" alt="No Image">
                                @else
                                    <span class="text-center">No Image!</span>
                                @endif
                            </div>
                            <div class="col-md-8 d-flex align-items-center justify-content-center">
                                <div class="card-body text-center">
                                    <h4 class="card-title">{{ $author->name }}</h4>
                                    <div class="d-flex justify-content-center mb-2">
                                        <img src="{{ $author->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
                                        <a href="{{ $author->link }}" target="_blank">{{ $author->link }}</a>
                                    </div>
                                    <div>
                                        @if(request()->query('sort') == null || request()->query('sort') == "followers")
                                                全期間でのフォロワー増加：
                                        @elseif(request()->query('sort') == "trending")
                                            @if(request()->query('period') == "week")
                                                一週間でのフォロワー増加：
                                            @elseif(request()->query('period') == "month")
                                                一か月でのフォロワー増加：
                                            @elseif(request()->query('period') == "year")
                                                一年でのフォロワー増加：
                                            @endif
                                        @endif
                                        @if(request()->query('sort') == null || request()->query('sort') == "followers" || request()->query('sort') == "trending")
                                            {{ $author->followers }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center justify-content-center mb-2">
                                @if($followedAuthors->contains('id', $author->id))
                                    <form action="{{ route('unfollow-author', $author->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">登録解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow-author', $author->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">登録</button>
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

        if (sort=='trending'){
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
        const period = document.getElementById("trendingOption").value;
        if (sort=='trending') {
            document.getElementById("trendingOption").style.display = "block";
            location = "{{ route('recommended-authors') }}?sort=" + sort + "&period=" + period;
        } else {
            document.getElementById("trendingOption").style.display = "none";
            location = "{{ route('recommended-authors') }}?sort=" + sort;
        }
    }

    // ページ読み込み時に初期化
    window.onload = initializeSortOptions;
</script>

@endsection
