<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Erro 404</h2>
    </x-slot>

    <div class="card">
        <h1 class="section-title">Página não encontrada</h1>
        <p class="section-text">{{ $exception->getMessage() ?: 'Página não encontrada' }}</p>
    </div>
</x-app-layout>

