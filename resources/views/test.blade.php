<!-- resources/views/test.blade.php -->

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


<!-- コンテンツ -->
<div class="container mt-5">
    <!-- 最初のコラプス -->
    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        クリックしてコラプスを表示
    </button>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            通常のコンテンツ
            <!-- ドロップダウン -->
            <div class="dropdown mt-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    ドロップダウン
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">アクション1</a></li>
                    <li><a class="dropdown-item" href="#">アクション2</a></li>
                    <li><a class="dropdown-item" href="#">アクション3</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 別のコラプス -->
    <button class="btn btn-primary mt-4" type="button" data-bs-toggle="collapse" data-bs-target="#anotherCollapse" aria-expanded="false" aria-controls="anotherCollapse">
        別のコラプスを表示
    </button>
    <div class="collapse" id="anotherCollapse">
        <div class="card card-body">
            別のコラプスのコンテンツ
        </div>
    </div>
</div>



</body>
</html>
