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

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="container">
        <!-- ヘッダー -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-lg rounded p-2 m-2">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- 左側の要素 -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('home') ? 'text-light bg-secondary border border-black rounded' : '' }}" href="/home">ホーム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('recommended-authors') ? 'text-light bg-secondary border border-black rounded' : '' }}" href="/recommended-authors">おすすめ著者</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('recommended-articles') ? 'text-light bg-secondary border border-black rounded' : '' }}" href="/recommended-articles">おすすめ記事</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('comments') ? 'text-light bg-secondary border border-black rounded' : '' }}" href="/comments">コメント</a>
                    </li>
                </ul>
                <!-- 右側の認証などの要素 -->
                @include('partials.auth-dropdown')
            </div>
        </nav>

        <!--フラッシュメッセージ-->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- タイトル -->
        @if(request()->is("home") | request()->is("recommended-authors") | request()->is("recommended-articles") | request()->is("comments"))
        <div class="row justify-content-center m-4">
            <div class="col-lg-8">
                <div class="card text-white bg-secondary shadow">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <h3 class="card-title text-center m-0">
                            @if(request()->is("home"))
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

        <!-- ページ固有の追加ヘッダー -->
        @yield('page-specific-header')

        <!--ページそれぞれの中身-->
        <main>
            @yield('content')
        </main>

    </div>
</div>

<!-- スクリプトなど -->
</body>
</html>
