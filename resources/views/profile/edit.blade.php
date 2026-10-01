<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Perfil</h2>
    </x-slot>

    <div class="stack">
        <div class="card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>