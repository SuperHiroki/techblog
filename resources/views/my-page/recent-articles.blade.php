{{-- resources/views/my-page/recent-articles.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="form-group">
            <label for="days-dropdown" class="m-1">日数を選択:</label>
            <select id="days-dropdown" class="form-control mb-4" onchange="goToRecentArticles()" style="border: 3px solid;">
                @for ($i = 1; $i <= 14; $i++)
                    <option value="{{ $i }}">{{ $i }} 日</option>
                @endfor
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var currentUrl = new URL(window.location.href);
    var pathSegments = currentUrl.pathname.split('/');
    var currentDays = pathSegments.length > 5 ? pathSegments[5] : '7'; // デフォルト値を '7' に設定

    var daysDropdown = document.getElementById("days-dropdown");
    daysDropdown.value = currentDays;
});

function goToRecentArticles() {
    var days = document.getElementById("days-dropdown").value;
    var currentUrl = new URL(window.location.href);
    var pathSegments = currentUrl.pathname.split('/');

    // 'days' の部分を新しい値に更新
    if (pathSegments.length > 5) {
        pathSegments[5] = days;
    }

    // 更新されたパスと元のクエリパラメータを組み合わせて新しいURLを構築
    currentUrl.pathname = pathSegments.join('/');
    window.location.href = currentUrl.href;
}
</script>


@include('partials.articles_sort')

@endsection
