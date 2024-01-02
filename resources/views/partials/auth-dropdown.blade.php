<!-- resources/views/layouts/partials/auth-dropdown.blade.php -->

<!-- ナビゲーションバーの右側の要素 -->
<ul class="navbar-nav ml-auto">
    @guest
        <li class="nav-item dropdown">
            <a id="navbarDropdown_guest" class="nav-link dropdown-toggle p-1 custom-header-link rounded" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                guest
                <img src="{{ asset('images/default-icons/avatar.png')}}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
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
        <li class="nav-item dropdown m-1 p-1">
            <a id="navbarDropdown" class="nav-link dropdown-toggle p-1 custom-header-link rounded" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
                @if(Auth::user()->icon_image)
                    <img src="{{ asset('storage/' . Auth::user()->icon_image) }}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                @else
                    <img src="{{ asset('images/default-icons/avatar.png')}}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                @endif
            </a>   

            <div class="dropdown-menu dropdown-menu-end m-1 py-1 px-2" aria-labelledby="navbarDropdown">
                <!-- マイページ -->
                <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('my-page/*') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('my-page.profile', Auth::user()->id) }}">
                    {{ __('My Page') }}
                </a>

                <!-- 設定 -->
                <a class="dropdown-item m-1 p-1 custom-header-link rounded {{ request()->is('settings/*') ? 'bg-light border border-secondary rounded' : '' }}" href="{{ route('settings.account', Auth::user()->id) }}">
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
