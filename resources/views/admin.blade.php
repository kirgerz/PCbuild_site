<x-layout title="Админ-панель">
    <x-header />

    <main class="admin-page">
        <section class="panel-card">
            <h2>Управление сборками</h2>
            <div class="admin-builds-list">
                @forelse ($builds as $build)
                    <div class="admin-build-row">
                        <div class="admin-build-info">
                            <div>
                                <span class="status-dot-inline dot-{{ $build->compatibility_status }}"></span>
                                <strong>#{{ $build->id }} {{ $build->title }}</strong>
                                <span class="build-category-tag">{{ $build->category->name }}</span>
                                @if ($build->is_blocked)
                                    <span class="blocked-badge">Заблокирована</span>
                                @else
                                    <span class="published-badge">Опубликована</span>
                                @endif
                            </div>
                            <p class="muted-text">
                                Автор: {{ $build->user->nickname }} ({{ $build->user->login }}) · {{ $build->part('cpu')?->name }} / {{ $build->part('gpu')?->name }} · {{ number_format($build->total_price, 0, ',', ' ') }} руб.
                            </p>
                        </div>

                        <div class="admin-build-controls">
                            <a href="{{ route('builds.edit', $build) }}" class="btn btn-secondary btn-sm">Редактировать</a>

                            <form method="POST" action="{{ route('admin.builds.block', $build) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm">
                                    {{ $build->is_blocked ? 'Разблокировать' : 'Блокировать' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.builds.destroy', $build) }}" onsubmit="return confirm('Удалить сборку?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="muted-text">Сборок нет.</p>
                @endforelse
            </div>

            @if ($builds->hasPages())
                <div class="pagination">
                    @if (! $builds->onFirstPage())
                        <a href="{{ $builds->previousPageUrl() }}" class="page-btn">Назад</a>
                    @endif
                    <span class="page-info">Страница {{ $builds->currentPage() }} из {{ $builds->lastPage() }}</span>
                    @if ($builds->hasMorePages())
                        <a href="{{ $builds->nextPageUrl() }}" class="page-btn">Далее</a>
                    @endif
                </div>
            @endif
        </section>

        <div class="admin-grid-2">
            <section class="panel-card">
                <h3>Смена роли пользователя</h3>
                <p class="muted-text" style="margin-bottom: 12px;">Снять роль с последнего администратора нельзя.</p>

                <form method="POST" action="{{ route('admin.users.role') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="user_id">Пользователь</label>
                        <select id="user_id" name="user_id" required>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">
                                    {{ $u->nickname }} ({{ $u->login }}) — {{ $u->role->name }} {{ $u->id === auth()->id() ? '[Вы]' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="role_id">Новая роль</label>
                        <select id="role_id" name="role_id" required>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить роль</button>
                </form>
            </section>

            <section class="panel-card">
                <h3>Добавление комплектующего</h3>
                <form method="POST" action="{{ route('admin.components.store') }}">
                    @csrf

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="type">Категория детали</label>
                            <select id="type" name="type" required>
                                @foreach (\App\Models\Component::TYPES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Наименование</label>
                            <input id="name" name="name" type="text" placeholder="Например: AMD Ryzen 7 7700" required>
                        </div>

                        <div class="form-group">
                            <label for="socket">Сокет (для процессора и платы)</label>
                            <input id="socket" name="socket" type="text" placeholder="AM4, AM5, LGA1700">
                        </div>

                        <div class="form-group">
                            <label for="ram_type">Тип памяти (для платы и ОЗУ)</label>
                            <input id="ram_type" name="ram_type" type="text" placeholder="DDR4, DDR5">
                        </div>

                        <div class="form-group">
                            <label for="power_w">Потребление / Мощность БП (Вт)</label>
                            <input id="power_w" name="power_w" type="number" value="0" min="0" max="2500" required>
                        </div>

                        <div class="form-group">
                            <label for="tier">Уровень мощности (1–5)</label>
                            <input id="tier" name="tier" type="number" value="3" min="1" max="5" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price">Цена (руб.)</label>
                        <input id="price" name="price" type="number" value="10000" min="100" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Добавить в базу</button>
                </form>
            </section>
        </div>
    </main>

    <x-footer />
</x-layout>
