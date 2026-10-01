<x-guest-layout>
    <p class="section-text">
        Esqueceu sua senha? Sem problemas. Informe seu email e enviaremos um link para redefinir sua senha.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <p class="help">Se o e-mail constar na nossa base, você receberá uma mensagem com o link de redefinição.</p>
        </div>

        <div class="row-end">
            <x-primary-button>Enviar link de redefinição</x-primary-button>
        </div>
    </form>
</x-guest-layout>
