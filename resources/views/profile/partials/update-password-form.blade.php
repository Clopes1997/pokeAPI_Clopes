<section>
    <header>
        <h2 class="section-title">
            Atualizar Senha
        </h2>
        <p class="section-text">
            Certifique-se de que sua conta está usando uma senha longa e aleatória para manter a segurança.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="stack">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Senha Atual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nova Senha')" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Senha')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
        </div>

        <div class="row">
            <x-primary-button>Salvar</x-primary-button>
        </div>
    </form>
</section>