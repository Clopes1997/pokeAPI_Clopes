<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </div>

        <div class="row">
            <label for="remember_me" class="section-text">
                <input id="remember_me" type="checkbox" name="remember">
                Lembrar-me
            </label>
        </div>

        <div class="row-end">
            @if (Route::has('password.request'))
                <a class="text-link" href="{{ route('password.request') }}">
                    Esqueceu sua senha?
                </a>
            @endif

            <x-primary-button>
                Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>