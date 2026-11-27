<x-layout title="Конфигуратор ПК">
    <x-header />

    <main class="builder-page">
        <div class="panel-card">
            <h2>{{ $build ? 'Редактирование сборки' : 'Конфигуратор ПК' }}</h2>
            <p class="muted-text" style="margin-bottom: 14px;">
                Подберите комплектующие. Индикатор ниже покажет итоговую стоимость и проверит совместимость разъёмов, памяти, блока питания и баланс процессора с видеокартой.
            </p>

            @if ($build?->is_blocked)
                <div class="traffic-banner status-red" style="margin-bottom: 14px;">
                    <span class="traffic-dot"></span>
                    <div class="traffic-text">
                        <strong>Статус:</strong> Эта сборка заблокирована администратором и не отображается на главной странице.
                    </div>
                </div>
            @endif

            <div id="live-traffic-box" class="traffic-banner status-green" style="margin-bottom: 18px;">
                <span class="traffic-dot"></span>
                <div class="traffic-text">
                    <strong id="live-status-title">Проверка:</strong>
                    <span id="live-status-msg">Выберите комплектующие.</span>
                </div>
                <div class="live-price-badge">
                    Итого: <strong id="live-total-price">0 руб.</strong>
                </div>
            </div>

            <form method="POST" action="{{ $build ? route('builds.update', $build) : route('builds.store') }}">
                @csrf
                @if ($build)
                    @method('PUT')
                @endif

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="title">Название сборки</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $build?->title) }}" placeholder="Например: Сборка для 2K игр" maxlength="150" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Категория</label>
                        <select id="category_id" name="category_id" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $build?->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="cpu_id">Процессор</label>
                        <select id="cpu_id" name="cpu_id" class="part-select" required>
                            @foreach ($cpus as $cpu)
                                <option value="{{ $cpu->id }}"
                                    data-socket="{{ $cpu->socket }}"
                                    data-power="{{ $cpu->power_w }}"
                                    data-tier="{{ $cpu->tier }}"
                                    data-price="{{ $cpu->price }}"
                                    data-name="{{ $cpu->name }}"
                                    @selected(old('cpu_id', $build?->part('cpu')?->id) == $cpu->id)>
                                    {{ $cpu->name }} ({{ $cpu->socket }}, {{ $cpu->power_w }} Вт) — {{ number_format($cpu->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="motherboard_id">Материнская плата</label>
                        <select id="motherboard_id" name="motherboard_id" class="part-select" required>
                            @foreach ($motherboards as $mb)
                                <option value="{{ $mb->id }}"
                                    data-socket="{{ $mb->socket }}"
                                    data-ram="{{ $mb->ram_type }}"
                                    data-power="{{ $mb->power_w }}"
                                    data-tier="{{ $mb->tier }}"
                                    data-price="{{ $mb->price }}"
                                    data-name="{{ $mb->name }}"
                                    @selected(old('motherboard_id', $build?->part('motherboard')?->id) == $mb->id)>
                                    {{ $mb->name }} ({{ $mb->socket }}, {{ $mb->ram_type }}) — {{ number_format($mb->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gpu_id">Видеокарта</label>
                        <select id="gpu_id" name="gpu_id" class="part-select" required>
                            @foreach ($gpus as $gpu)
                                <option value="{{ $gpu->id }}"
                                    data-power="{{ $gpu->power_w }}"
                                    data-tier="{{ $gpu->tier }}"
                                    data-price="{{ $gpu->price }}"
                                    data-name="{{ $gpu->name }}"
                                    @selected(old('gpu_id', $build?->part('gpu')?->id) == $gpu->id)>
                                    {{ $gpu->name }} ({{ $gpu->power_w }} Вт) — {{ number_format($gpu->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ram_id">Оперативная память</label>
                        <select id="ram_id" name="ram_id" class="part-select" required>
                            @foreach ($rams as $ram)
                                <option value="{{ $ram->id }}"
                                    data-ram="{{ $ram->ram_type }}"
                                    data-power="{{ $ram->power_w }}"
                                    data-tier="{{ $ram->tier }}"
                                    data-price="{{ $ram->price }}"
                                    data-name="{{ $ram->name }}"
                                    @selected(old('ram_id', $build?->part('ram')?->id) == $ram->id)>
                                    {{ $ram->name }} ({{ $ram->ram_type }}) — {{ number_format($ram->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="psu_id">Блок питания</label>
                        <select id="psu_id" name="psu_id" class="part-select" required>
                            @foreach ($psus as $psu)
                                <option value="{{ $psu->id }}"
                                    data-power="{{ $psu->power_w }}"
                                    data-tier="{{ $psu->tier }}"
                                    data-price="{{ $psu->price }}"
                                    data-name="{{ $psu->name }}"
                                    @selected(old('psu_id', $build?->part('psu')?->id) == $psu->id)>
                                    {{ $psu->name }} ({{ $psu->power_w }} Вт) — {{ number_format($psu->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="case_id">Корпус</label>
                        <select id="case_id" name="case_id" class="part-select" required>
                            @foreach ($cases as $pcCase)
                                <option value="{{ $pcCase->id }}"
                                    data-price="{{ $pcCase->price }}"
                                    data-name="{{ $pcCase->name }}"
                                    @selected(old('case_id', $build?->part('case')?->id) == $pcCase->id)>
                                    {{ $pcCase->name }} — {{ number_format($pcCase->price, 0, ',', ' ') }} руб.
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Описание сборки</label>
                    <textarea id="description" name="description" rows="4" placeholder="Краткий комментарий к сборке..." required>{{ old('description', $build?->description) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        {{ $build ? 'Сохранить изменения' : 'Опубликовать сборку' }}
                    </button>
                    @if ($build)
                        <a href="{{ route('builder') }}" class="btn btn-secondary">Новая сборка</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="panel-card">
            <h3>Мои сборки ({{ $ownBuilds->count() }})</h3>
            <div class="compact-builds-list">
                @forelse ($ownBuilds as $item)
                    <div class="compact-build-item">
                        <div>
                            <span class="status-dot-inline dot-{{ $item->compatibility_status }}"></span>
                            <strong>{{ $item->title }}</strong>
                            <span class="muted-text">({{ $item->category->name }}, {{ number_format($item->total_price, 0, ',', ' ') }} руб.)</span>
                            @if ($item->is_blocked)
                                <span class="blocked-badge">Заблокирована</span>
                            @endif
                        </div>
                        <a href="{{ route('builds.edit', $item) }}" class="btn btn-secondary btn-sm">Редактировать</a>
                    </div>
                @empty
                    <p class="muted-text" style="margin-top: 8px;">Вы ещё не добавили ни одной сборки.</p>
                @endforelse
            </div>
        </div>
    </main>

    <script>
    function recalculateLiveCompatibility() {
        const cpuOpt = document.getElementById('cpu_id').selectedOptions[0];
        const mbOpt = document.getElementById('motherboard_id').selectedOptions[0];
        const gpuOpt = document.getElementById('gpu_id').selectedOptions[0];
        const ramOpt = document.getElementById('ram_id').selectedOptions[0];
        const psuOpt = document.getElementById('psu_id').selectedOptions[0];
        const caseOpt = document.getElementById('case_id').selectedOptions[0];
        if (!cpuOpt || !mbOpt || !gpuOpt || !ramOpt || !psuOpt || !caseOpt) return;

        const totalPrice = (+cpuOpt.dataset.price) + (+mbOpt.dataset.price) + (+gpuOpt.dataset.price) + (+ramOpt.dataset.price) + (+psuOpt.dataset.price) + (+caseOpt.dataset.price);
        const requiredWatts = (+cpuOpt.dataset.power) + (+gpuOpt.dataset.power) + 100;
        const psuWatts = +psuOpt.dataset.power;
        const cpuTier = +cpuOpt.dataset.tier;
        const gpuTier = +gpuOpt.dataset.tier;

        const redErrors = [];
        const yellowWarnings = [];

        if (cpuOpt.dataset.socket !== mbOpt.dataset.socket) {
            redErrors.push(`сокет процессора (${cpuOpt.dataset.socket}) не совпадает с сокетом платы (${mbOpt.dataset.socket})`);
        }
        if (ramOpt.dataset.ram !== mbOpt.dataset.ram) {
            redErrors.push(`память (${ramOpt.dataset.ram}) не подходит к плате (${mbOpt.dataset.ram})`);
        }
        if (psuWatts < requiredWatts) {
            redErrors.push(`мощности БП (${psuWatts} Вт) недостаточно (нужно минимум ${requiredWatts} Вт)`);
        }

        const box = document.getElementById('live-traffic-box');
        const title = document.getElementById('live-status-title');
        const msg = document.getElementById('live-status-msg');
        const priceEl = document.getElementById('live-total-price');

        priceEl.textContent = totalPrice.toLocaleString('ru-RU') + ' руб.';
        box.className = 'traffic-banner';

        if (redErrors.length > 0) {
            box.classList.add('status-red');
            title.textContent = 'Несовместимо:';
            msg.textContent = redErrors.join('; ') + '.';
            return;
        }

        if ((gpuTier - cpuTier) >= 2) {
            yellowWarnings.push(`процессор (${cpuOpt.dataset.name}) слишком слабый для видеокарты (${gpuOpt.dataset.name}), будет упор в процессор`);
        } else if ((cpuTier - gpuTier) >= 3) {
            yellowWarnings.push(`видеокарта (${gpuOpt.dataset.name}) слабовата для процессора (${cpuOpt.dataset.name})`);
        }
        if (psuWatts < requiredWatts + 70) {
            yellowWarnings.push(`блок питания (${psuWatts} Вт) выбран почти впритык (потребление около ${requiredWatts} Вт)`);
        }

        if (yellowWarnings.length > 0) {
            box.classList.add('status-yellow');
            title.textContent = 'Предупреждение:';
            msg.textContent = yellowWarnings.join('; ') + '.';
            return;
        }

        box.classList.add('status-green');
        title.textContent = 'Совместимо:';
        msg.textContent = `комплектующие подходят друг к другу (сокет ${cpuOpt.dataset.socket}, память ${ramOpt.dataset.ram}, БП ${psuWatts} Вт).`;
    }

    document.querySelectorAll('.part-select').forEach(sel => {
        sel.addEventListener('change', recalculateLiveCompatibility);
    });
    recalculateLiveCompatibility();
    </script>

    <x-footer />
</x-layout>
