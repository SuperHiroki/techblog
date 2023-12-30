<!-- resources/views/layouts/partials/auth-dropdown.blade.php -->

<!-- ナビゲーションバーの右側の要素 -->
<ul class="navbar-nav ml-auto">
    @guest
        <li class="nav-item dropdown">
            <a id="navbarDropdown_guest" class="nav-link dropdown-toggle p-1 custom-header-link rounded" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                guest
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown_guest">
                <!-- ログイン -->
                <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('login') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
                
                <!-- 登録-->
                @if (Route::has('register'))
                    <a class="nav-link m-1 p-1 custom-header-link rounded {{ request()->is('register') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            </div>
        </li>
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle p-1 custom-header-link rounded" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end p-1" aria-labelledby="navbarDropdown">
                <!-- マイページ -->
                <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('my-page.public-profile') }}">
                    {{ __('My Page') }}
                </a>

                <!-- 設定 -->
                <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('settings/*') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('settings.account') }}">
                    {{ __('Settings') }}
                </a>

                <!-- ログアウト -->
                <a class="dropdown-item m-1 p-1 custom-header-link rounded" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                <!-- 管理者のみ -->
                @if (Auth::user()->is_admin)
                    <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('authors') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('authors.index') }}">
                        {{ __('Authors ~ Admin Panel') }}
                    </a>
                    <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('articles') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('articles.index') }}">
                        {{ __('Articles ~ Admin Panel') }}
                    </a>
                @endif
            </div>
        </li>
    @endguest
</ul>
