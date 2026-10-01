<section>
    <header>
        <h2 class="section-title">
            Informações do Perfil
        </h2>
        <p class="section-text">
            Atualize as informações do perfil e o endereço de email da sua conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="stack">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="section-text">
                        Seu endereço de email não foi verificado.

                        <button form="send-verification" class="text-link">
                            Clique aqui para reenviar o email de verificação.
                        </button>
                    </p>

                </div>
            @endif
        </div>

        <div class="row">
            <x-primary-button>Salvar</x-primary-button>
        </div>
    </form>
</section>