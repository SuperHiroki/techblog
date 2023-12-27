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
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- 通常のナビゲーションバー -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- 左側のナビゲーションバー -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="#">ホーム</a></li>
                        <li class="nav-item"><a class="nav-link" href="/recommended-authors">おすすめ著者</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">おすすめ記事</a></li>
                    </ul>

                    <!-- 右側の認証リンク -->
                    @include('partials.auth-dropdown')
                </div>
            </div>
        </nav>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ページ固有の追加ヘッダー -->
        @yield('page-specific-header')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- スクリプトなど -->
</body>
</html>
