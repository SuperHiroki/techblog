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
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('home' , '/') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('home') }}">ホーム</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('recommended-authors') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('recommended-authors') }}">おすすめ著者</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('recommended-articles') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('recommended-articles') }}">おすすめ記事</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('comments') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('comments') }}">コメント</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link p-1 custom-header-link rounded {{ request()->is('users') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('users') }}">ユーザ一覧</a>
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
                <div class="alert alert-danger flush_msg_default">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger flush_msg_default">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success flush_msg_default">
                    {{ session('success') }}
                </div>
            @endif

            <!--非同期処理で起きるフラッシュメッセージ-->
            <div id="flush_error" class="alert alert-danger" style="display:none"></div>
            <div id="flush_success" class="alert alert-success" style="display:none"></div>

        </div>
    </div>

    <!--ページごとに異なる-->
    <div class="container mb-5" id="containerContent">
        <!-- タイトル -->
        @if(request()->is('/') | request()->is("home") | request()->is("recommended-authors") | request()->is("recommended-articles") | request()->is("comments") | request()->is("users"))
            <div class="row justify-content-center m-4">
                <div class="col-lg-8">
                    <div class="card text-white bg-secondary shadow">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <h3 class="card-title text-center m-0">
                                {{----@elseif(request()->is(parse_url(route('recommended-authors'), PHP_URL_PATH)))のような書き方も可能。----}}
                                @if(request()->is('/') || request()->is('home'))
                                    ホーム
                                @elseif(request()->is("recommended-authors"))
                                    おすすめ著者
                                @elseif(request()->is("recommended-articles"))
                                    おすすめ記事
                                @elseif(request()->is("comments"))
                                    コメント
                                @elseif(request()->is("users"))
                                    ユーザ一覧
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

<!--全てのページに共通するJS.-->
@include('js.common-in-all-js')

</body>
</html>
