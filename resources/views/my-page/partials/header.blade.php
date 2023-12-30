<!-- resources/views/my-page/partials/header.blade.php -->

<!-- ヘッダー -->
<nav class="navbar navbar-expand-lg navbar-light bg-light bg-white shadow-lg rounded m-2 p-2">
    <!-- ハンバーガーメニュー -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ブランドまたはタイトル (必要に応じて) -->
    <!-- <a class="navbar-brand" href="#"></a> -->

    <!-- ナビゲーションリンク -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/public-profile') ? 'bg-light border border-primary rounded' : '' }}" id="account-link" href="{{ route('my-page.public-profile') }}">公開プロフィール</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/followed-authors') ? 'bg-light border border-primary rounded' : '' }}" id="account-link" href="{{ route('my-page.followed-authors') }}">フォローした著者</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/recent-articles') ? 'bg-light border border-primary rounded' : '' }}" id="profile-link" href="{{ route('my-page.recent-articles') }}">最近の記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/likes') ? 'bg-light border border-primary rounded' : '' }}" id="profile-link" href="{{ route('my-page.likes') }}">いいね</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/bookmarks') ? 'bg-light border border-primary rounded' : '' }}" id="profile-link" href="{{ route('my-page.bookmarks') }}">ブックマーク</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-page/archives') ? 'bg-light border border-primary rounded' : '' }}" id="profile-link" href="{{ route('my-page.archives') }}">アーカイブ</a>
            </li>
        </ul>
    </div>
</nav>

<!-- タイトル -->
<div class="row justify-content-center m-4">
    <div class="col-lg-8">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body d-flex align-items-center justify-content-center">
                <h3 class="card-title text-center m-0">
                    @if(request()->is("my-page/public-profile"))
                        公開プロフィール
                    @elseif(request()->is("my-page/followed-authors"))
                        フォローした著者
                    @elseif(request()->is("my-page/recent-articles"))
                        最近の記事
                    @elseif(request()->is("my-page/likes"))
                        いいね
                    @elseif(request()->is("my-page/bookmarks"))
                        ブックマーク
                    @elseif(request()->is("my-page/archives"))
                        アーカイブ
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>
