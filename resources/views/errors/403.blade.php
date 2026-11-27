<x-layout title="Доступ запрещён">
    <x-header />
    <main class="narrow-panel">
        <div class="panel-card">
            <h2>Ошибка 403</h2>
            <p class="muted-text" style="margin: 10px 0 16px;">У вашего аккаунта нет прав доступа к этому разделу.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">На главную</a>
        </div>
    </main>
    <x-footer />
</x-layout>
