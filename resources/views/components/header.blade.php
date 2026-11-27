<header class="top-header">
    <div class="header-left">
        <a href="{{ route('home') }}" class="nav-box brand-box">
            PC Builder
        </a>

        @if (auth()->user()?->hasRole('Сборщик', 'Администратор'))
            <a href="{{ route('builder') }}" class="nav-box">
                Собрать ПК
            </a>
        @endif

        @if (auth()->user()?->hasRole('Администратор'))
            <a href="{{ route('admin') }}" class="nav-box">
                Админ-панель
            </a>
        @endif
    </div>

    <div class="header-right">
        @auth
            <a href="{{ route('profile') }}" class="profile-widget" title="Профиль">
                <div class="avatar-circle">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v2h20v-2c0-3.33-6.67-5-10-5z"/>
                    </svg>
                </div>
                <span class="profile-nickname">{{ auth()->user()->nickname }}</span>
            </a>
        @else
            <div class="guest-links">
                <a href="{{ route('login') }}" class="nav-box">Вход</a>
                <a href="{{ route('register') }}" class="nav-box">Регистрация</a>
            </div>
        @endauth
    </div>
</header>
