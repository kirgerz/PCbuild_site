<x-layout title="Профиль">
    <x-header />

    <main class="profile-page">
        <div class="profile-grid">
            <section class="panel-card">
                <div class="profile-card-head">
                    <div class="avatar-circle avatar-lg">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v2h20v-2c0-3.33-6.67-5-10-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2>{{ $user->nickname }}</h2>
                        <p class="muted-text">Логин: {{ $user->login }} · Роль: {{ $user->role->name }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="nickname">Никнейм</label>
                        <input id="nickname" name="nickname" type="text" value="{{ old('nickname', $user->nickname) }}" maxlength="50" required>
                    </div>

                    <div class="form-group">
                        <label for="bio">Описание профиля</label>
                        <textarea id="bio" name="bio" rows="4" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>

                <hr class="divider">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Выйти из аккаунта</button>
                </form>
            </section>

            @if ($user->hasRole('Сборщик', 'Администратор'))
                <section class="panel-card">
                    <div class="section-head-inline">
                        <h3>Мои сборки ({{ $ownBuilds->count() }})</h3>
                        <a href="{{ route('builder') }}" class="btn btn-primary btn-sm">Добавить сборку</a>
                    </div>

                    @forelse ($ownBuilds as $build)
                        <div class="profile-build-card">
                            <div class="profile-build-top">
                                <div>
                                    <span class="build-category-tag">{{ $build->category->name }}</span>
                                    <h4>{{ $build->title }}</h4>
                                </div>
                                <strong class="price-value">{{ number_format($build->total_price, 0, ',', ' ') }} руб.</strong>
                            </div>
                            <p class="muted-text">
                                {{ $build->part('cpu')?->name }}, {{ $build->part('gpu')?->name }}, {{ $build->part('ram')?->name }}, {{ $build->part('case')?->name ?? 'Корпус не указан' }}
                            </p>
                            <div class="traffic-banner status-{{ $build->compatibility_status }}">
                                <span class="traffic-dot"></span>
                                <span>{{ $build->compatibility_message }}</span>
                            </div>
                            <div class="profile-build-actions">
                                <a href="{{ route('builds.edit', $build) }}" class="btn btn-secondary btn-sm">Редактировать</a>
                                @if ($build->is_blocked)
                                    <span class="blocked-badge">Заблокирована</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="muted-text" style="margin-top: 10px;">Сборок пока нет.</p>
                    @endforelse
                </section>
            @endif
        </div>
    </main>

    <x-footer />
</x-layout>
