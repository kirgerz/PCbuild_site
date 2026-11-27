<x-layout title="Вход">
    <x-header />

    <main class="narrow-panel">
        <div class="panel-card">
            <h2>Вход</h2>
            <p class="muted-text" style="margin-bottom: 14px;">Тестовые аккаунты: user, builder, admin (пароль: password123)</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </main>

    <x-footer />
</x-layout>
