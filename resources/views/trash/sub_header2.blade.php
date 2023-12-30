<!-- resources/views/my-page/partials/header.blade.php -->

<!-- ヘッダー -->
<div class="m-2">
    <nav class="navbar navbar-expand-lg navbar-light bg-light bg-white shadow-lg rounded">
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
                    <a class="nav-link {{ request()->is('settings/account') ? 'bg-light border border-primary rounded' : '' }}" id="account-link" href="{{ route('settings.account') }}">アカウント設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings/public-profile') ? 'bg-light border border-primary rounded' : '' }}" id="profile-link" href="{{ route('settings.public-profile') }}">公開プロフィール設定</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- タイトル -->
<div class="row justify-content-center m-2">
    <div class="col-lg-8">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body d-flex align-items-center justify-content-center">
                <h3 class="card-title text-center m-0">
                    @if(request()->is("settings/account"))
                        アカウント設定
                    @elseif(request()->is("settings/public-profile"))
                        公開プロフィール設定
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>