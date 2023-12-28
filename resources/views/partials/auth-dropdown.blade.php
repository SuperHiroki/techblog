<!-- resources/views/layouts/partials/auth-dropdown.blade.php -->

<!-- Right side authentication links -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication check -->
    @guest
        <li class="nav-item dropdown">
            <a id="navbarDropdown_guest" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                guest
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown_guest">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                @if (Route::has('register'))
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            </div>
        </li>
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <!-- Add My Page link -->
                <a class="dropdown-item" href="{{ route('my-page.public-profile') }}">
                    {{ __('My Page') }}
                </a>

                <!-- Add Profile link -->
                <a class="dropdown-item" href="{{ route('settings.account') }}">
                    {{ __('Settings') }}
                </a>

                <!-- Existing Logout link -->
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                @if (Auth::user()->is_admin)
                    <a class="dropdown-item" href="{{ route('authors.index') }}">
                        {{ __('Authors ~ Admin Panel') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('articles.index') }}">
                        {{ __('Articles ~ Admin Panel') }}
                    </a>
                @endif
            </div>
        </li>
    @endguest
</ul>
