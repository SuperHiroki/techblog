<!-- resources/views/layouts/app.blade.php -->

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!--****************************************************************************-->
    <!--****************************************************************************-->
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/base/favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:image" content="{{ asset('images/base/thumbnail.png') }}" />

    <!-- Twitter -->
    <meta name="twitter:image" content="{{ asset('images/base/thumbnail.png') }}" />

    <!-- Description -->
    <meta name="description" content="当ウェブアプリを使用することで、簡単にテックブログを管理することができます。">

    <!--CSS-->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!--JJS-->
    <!--<script src="{{ asset('js/common-async-fetch.js') }}"></script>-->
</head>
<body>
<div id="app">

    <!-- 共通部分 -->
    <div id="fixed-header" class="custom-fixed-header">
        <div class="container">
            <!-- ヘッダー -->
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-lg rounded p-2 mx-2">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- 左側の要素 -->
                    <ul class="navbar-nav me-auto p-1 pt-2">
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('home' , '/') ? 'bg-light border border-secondary rounded' : '' }}" href="/home">ホーム</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('recommended-authors') ? 'bg-light border border-secondary rounded' : '' }}" href="/recommended-authors">おすすめ著者</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('recommended-articles') ? 'bg-light border border-secondary rounded' : '' }}" href="/recommended-articles">おすすめ記事</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('comments') ? 'bg-light border border-secondary rounded' : '' }}" href="/comments">コメント</a>
                        </li>
                    </ul>
                    <!-- 右側の認証などの要素 -->
                    @include('partials.auth-dropdown')
                </div>
            </nav>

            <!-- ページ固有の追加ヘッダー -->
            <div id="page-specific-header">
                @yield('page-specific-header')
            </div>

            <!--フラッシュメッセージ-->
            @if ($errors->any())
                <div class="alert alert-danger flush_msg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger flush_msg">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success flush_msg">
                    {{ session('success') }}
                </div>
            @endif

            <!--非同期処理で起きるフラッシュメッセージ-->
            <div id="flush_error" class="alert alert-danger flush_msg" style="display:none"></div>
            <div id="flush_success" class="alert alert-success flush_msg" style="display:none"></div>

        </div>
    </div>

    <!--ページごとに異なる-->
    <div class="container" id="containerContent">
        <!-- タイトル -->
        @if(request()->is('/') | request()->is("home") | request()->is("recommended-authors") | request()->is("recommended-articles") | request()->is("comments"))
        <div class="row justify-content-center m-4">
            <div class="col-lg-8">
                <div class="card text-white bg-secondary shadow">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <h3 class="card-title text-center m-0">
                            @if(request()->is('/') || request()->is('home'))
                                ホーム
                            @elseif(request()->is("recommended-authors"))
                                おすすめ著者
                            @elseif(request()->is("recommended-articles"))
                                おすすめ記事
                            @elseif(request()->is("comments"))
                                コメント
                            @endif   
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!--ページそれぞれの中身-->
        <main>
            @yield('content')
        </main>

    </div>
</div>

<script>
//ヘッダーとコンテンツ
var fixedHeader = document.getElementById('fixed-header');
var containerContent = document.getElementById('containerContent');

//コンテントのpaddingTopを再調整する関数
function adjustPaddingTop(){
    var headerHeight = fixedHeader.offsetHeight;
    containerContent.style.paddingTop = headerHeight + 'px';
}

//ページ読み込み時に、コンテンツが固定されたヘッダーに隠れないようにする。
document.addEventListener("DOMContentLoaded", function() {
    adjustPaddingTop();
});

// 監視することでフラッシュメッセージの表示と非表示を切り替える。
const targetNodes = document.getElementsByClassName('flush_msg');
const config = { characterData: true, childList: true };
const callback = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if(mutation.type === 'characterData' || mutation.type === 'childList') {
            const target = mutation.target;
            if(target.textContent.length === 0) {
                target.style.display = 'none';
            } else {
                target.style.display = 'block';
            }
        }
    }
};
const observer = new MutationObserver(callback);
Array.from(targetNodes).forEach(node => {
    observer.observe(node, config);
});


//動的に、コンテンツが固定されたヘッダーに隠れないようにする。
const config_fixed_header = { attributes: true, characterData: true, childList: true, subtree: true };
const callback_fixed_header = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if(mutation.type === 'attributes' || mutation.type === 'characterData'  || mutation.type === 'childList' || mutation.type === 'subtree') {
            const target = mutation.target;
            adjustPaddingTop();
        }
    }
};
const observer_fixed_header = new MutationObserver(callback_fixed_header);
observer_fixed_header.observe(fixedHeader, config_fixed_header);


</script>

</body>
</html>
