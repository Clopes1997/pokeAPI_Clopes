<x-guest-layout>
    <p class="section-text">
        Obrigado por se cadastrar! Antes de começar, verifique seu email clicando no link que enviamos. Se você não recebeu o email, podemos enviar novamente.
    </p>

    <div class="row-end">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>Reenviar email de verificação</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-ghost">Sair</button>
        </form>
    </div>
</x-guest-layout>
