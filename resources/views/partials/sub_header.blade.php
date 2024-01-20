<!-- resources/views/layouts/partials/sub_header.blade.php -->

<!-- ヘッダー -->
<nav id="aaa" class="navbar navbar-expand-lg navbar-light bg-light bg-white shadow-lg rounded m-2 p-2">
    <!-- ハンバーガーメニュー -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ブランドまたはタイトル (必要に応じて) -->
    <!-- <a class="navbar-brand" href="#"></a> -->

    <!-- ナビゲーションリンク -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav p-1 pt-2">
            @if(request()->is("my-page/*"))
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/profile') ? 'bg-light border border-secondary rounded' : '' }}" id="account-link" href="{{ route('my-page.profile', $user->id) }}">公開プロフィール</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/followed-authors') ? 'bg-light border border-secondary rounded' : '' }}" id="account-link" href="{{ route('my-page.followed-authors', $user->id) }}">フォロー著者</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/recent-articles/*') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('my-page.recent-articles', ['user' => $user->id, 'days' => 7]) }}">新着記事</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/likes') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('my-page.likes', $user->id) }}">いいね記事</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/bookmarks') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('my-page.bookmarks', $user->id) }}">ブックマーク記事</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/archives') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('my-page.archives', $user->id) }}">アーカイブ記事</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*/trashes') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('my-page.trashes', $user->id) }}">ゴミ箱記事</a>
                </li>
            @elseif(request()->is("settings/*"))
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('settings/*/account') ? 'bg-light border border-secondary rounded' : '' }}" id="account-link" href="{{ route('settings.account', Auth::user()->id) }}">アカウント設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('settings/*/public-profile') ? 'bg-light border border-secondary rounded' : '' }}" id="profile-link" href="{{ route('settings.public-profile', Auth::user()->id) }}">公開プロフィール設定</a>
                </li>
            @endif
        </ul>
    </div>
</nav>

<!-- タイトル -->
<div class="row justify-content-center mx-4 my-2">
    <div class="col-lg-8">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body d-flex align-items-center justify-content-center">
                <h3 class="card-title text-center m-0">
                    <strong>{{ $user->name }} </strong>の
                    @if(request()->is("my-page/*"))
                        @if(request()->is("my-page/*/profile"))
                            公開プロフィール
                        @elseif(request()->is("my-page/*/followed-authors"))
                            フォローしている著者
                        @elseif(request()->is("my-page/*/recent-articles/*"))
                            フォロー著者の新着記事
                        @elseif(request()->is("my-page/*/likes"))
                            いいねした記事
                        @elseif(request()->is("my-page/*/bookmarks"))
                            ブックマークした記事
                        @elseif(request()->is("my-page/*/archives"))
                            アーカイブした記事
                        @elseif(request()->is("my-page/*/trashes"))
                            アーカイブした記事
                        @endif
                    @elseif(request()->is("settings/*"))
                        @if(request()->is("settings/*/account"))
                            アカウント設定
                        @elseif(request()->is("settings/*/public-profile"))
                            公開プロフィール設定
                        @endif
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>
