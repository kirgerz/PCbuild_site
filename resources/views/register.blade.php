<x-layout title="Регистрация">
    <x-header />

    <main class="narrow-panel">
        <div class="panel-card">
            <h2>Регистрация</h2>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" maxlength="30" required>
                </div>

                <div class="form-group">
                    <label for="nickname">Никнейм</label>
                    <input id="nickname" name="nickname" type="text" value="{{ old('nickname') }}" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Повторите пароль</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </div>
    </main>

    <x-footer />
</x-layout>
