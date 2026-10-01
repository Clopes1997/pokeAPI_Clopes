<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Erro 403</h2>
    </x-slot>

    <div class="card">
        <h1 class="section-title">Você não tem permissão para realizar esta ação</h1>
        <p class="section-text">{{ $exception->getMessage() ?: 'Você não tem permissão para realizar esta ação' }}</p>
    </div>
</x-app-layout>

