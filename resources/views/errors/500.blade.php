<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Erro 500</h2>
    </x-slot>

    <div class="card">
        <h1 class="section-title">Erro ao comunicar com a API de Pokémon</h1>
        <p class="section-text">{{ $exception->getMessage() ?: 'Erro ao comunicar com a API de Pokémon' }}</p>
    </div>
</x-app-layout>

