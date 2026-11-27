<x-layout title="Главная">
    <x-header />

    <main>
        <nav class="category-bar" aria-label="Категории сборок">
            <a href="{{ route('home') }}" class="cat-link {{ $activeCategory === null ? 'active' : '' }}">
                Все сборки
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('category', $cat->slug) }}" class="cat-link {{ $activeCategory?->id === $cat->id ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </nav>

        <section class="builds-feed">
            @forelse ($builds as $build)
                <article class="build-row">
                    <!-- Левый блок: автор сборки -->
                    <aside class="builder-card">
                        <div class="builder-card-top">
                            <div class="avatar-circle avatar-lg">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v2h20v-2c0-3.33-6.67-5-10-5z"/>
                                </svg>
                            </div>
                            <div class="builder-identity">
                                <span class="builder-role">{{ strtolower($build->user->role->name) }}</span>
                                <strong class="builder-nick">{{ $build->user->nickname }}</strong>
                            </div>
                        </div>

                        <div class="builder-bio">
                            {{ $build->user->bio ?: 'Без описания' }}
                        </div>

                        <div class="builder-footer-meta">
                            <span>Дата: {{ $build->created_at->format('d.m.Y') }}</span>
                            @if (auth()->user()?->hasRole('Администратор') || auth()->id() === $build->user_id)
                                <a href="{{ route('builds.edit', $build) }}" class="edit-link">Редактировать</a>
                            @endif
                        </div>
                    </aside>

                    <!-- Правый блок: сборка и комплектующие -->
                    <div class="build-content-card">
                        <div class="build-header">
                            <div>
                                <span class="build-category-tag">{{ $build->category->name }}</span>
                                <h2 class="build-title">{{ $build->title }}</h2>
                            </div>
                            <div class="build-price-box">
                                <span class="price-label">Стоимость</span>
                                <strong class="price-value">{{ number_format($build->total_price, 0, ',', ' ') }} руб.</strong>
                            </div>
                        </div>

                        <!-- Статус проверки совместимости -->
                        <div class="traffic-banner status-{{ $build->compatibility_status }}">
                            <span class="traffic-dot"></span>
                            <div class="traffic-text">
                                @if ($build->compatibility_status === 'green')
                                    <strong>Совместимо:</strong>
                                @elseif ($build->compatibility_status === 'yellow')
                                    <strong>Предупреждение:</strong>
                                @else
                                    <strong>Несовместимо:</strong>
                                @endif
                                <span>{{ $build->compatibility_message }}</span>
                            </div>
                        </div>

                        <p class="build-description">{{ $build->description }}</p>

                        <!-- Таблица комплектующих -->
                        @php
                            $cpu = $build->part('cpu');
                            $mb = $build->part('motherboard');
                            $gpu = $build->part('gpu');
                            $ram = $build->part('ram');
                            $psu = $build->part('psu');
                            $pcCase = $build->part('case');
                        @endphp
                        <table class="parts-table">
                            <tbody>
                                <tr>
                                    <th>Процессор</th>
                                    <td>{{ $cpu?->name ?? 'Не указан' }} @if($cpu)<span class="part-meta">({{ $cpu->socket }}, {{ $cpu->power_w }} Вт)</span>@endif</td>
                                    <td class="part-price">{{ $cpu ? number_format($cpu->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Материнская плата</th>
                                    <td>{{ $mb?->name ?? 'Не указана' }} @if($mb)<span class="part-meta">({{ $mb->socket }}, {{ $mb->ram_type }})</span>@endif</td>
                                    <td class="part-price">{{ $mb ? number_format($mb->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Видеокарта</th>
                                    <td>{{ $gpu?->name ?? 'Не указана' }} @if($gpu)<span class="part-meta">({{ $gpu->power_w }} Вт)</span>@endif</td>
                                    <td class="part-price">{{ $gpu ? number_format($gpu->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Оперативная память</th>
                                    <td>{{ $ram?->name ?? 'Не указана' }} @if($ram)<span class="part-meta">({{ $ram->ram_type }})</span>@endif</td>
                                    <td class="part-price">{{ $ram ? number_format($ram->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Блок питания</th>
                                    <td>{{ $psu?->name ?? 'Не указан' }} @if($psu)<span class="part-meta">({{ $psu->power_w }} Вт)</span>@endif</td>
                                    <td class="part-price">{{ $psu ? number_format($psu->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Корпус</th>
                                    <td>{{ $pcCase?->name ?? 'Не указан' }}</td>
                                    <td class="part-price">{{ $pcCase ? number_format($pcCase->price, 0, ',', ' ') . ' руб.' : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Комментарии -->
                        <div class="comments-section">
                            <h3 class="comments-heading">Комментарии ({{ $build->comments->count() }})</h3>

                            <div class="comments-list">
                                @forelse ($build->comments as $comment)
                                    <div class="comment-item">
                                        <div class="comment-header">
                                            <strong>{{ $comment->user->nickname }}</strong>
                                            <span class="comment-role">{{ $comment->user->role->name }}</span>
                                            <span class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>

                                            @if (auth()->user()?->hasRole('Администратор') || auth()->id() === $comment->user_id)
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="comment-delete-btn">Удалить</button>
                                                </form>
                                            @endif
                                        </div>
                                        <p class="comment-body">{{ $comment->body }}</p>
                                    </div>
                                @empty
                                    <p class="muted-text">Комментариев пока нет.</p>
                                @endforelse
                            </div>

                            @auth
                                <form method="POST" action="{{ route('comments.store', $build) }}" class="comment-form">
                                    @csrf
                                    <input type="text" name="body" placeholder="Оставить комментарий..." maxlength="1000" required>
                                    <button type="submit" class="btn btn-primary">Отправить</button>
                                </form>
                            @else
                                <p class="muted-text"><a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.</p>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel-card">
                    <p class="muted-text">В выбранной категории пока нет сборок.</p>
                </div>
            @endforelse
        </section>

        @if ($builds->hasPages())
            <nav class="pagination" aria-label="Страницы">
                @if ($builds->onFirstPage())
                    <span class="page-btn disabled">Назад</span>
                @else
                    <a href="{{ $builds->previousPageUrl() }}" class="page-btn">Назад</a>
                @endif
                <span class="page-info">Страница {{ $builds->currentPage() }} из {{ $builds->lastPage() }}</span>
                @if ($builds->hasMorePages())
                    <a href="{{ $builds->nextPageUrl() }}" class="page-btn">Далее</a>
                @else
                    <span class="page-btn disabled">Далее</span>
                @endif
            </nav>
        @endif
    </main>

    <x-footer />
</x-layout>
