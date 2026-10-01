<section class="stack">
    <header>
        <h2 class="section-title">
            Excluir Conta
        </h2>
        <p class="section-text">
            Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que deseja manter.
        </p>
    </header>

    <div>
        <a class="btn btn-danger" href="#confirm-user-deletion">Excluir Conta</a>
    </div>

    <div id="confirm-user-deletion" class="modal-overlay">
        <div class="modal">
            <div class="modal-body">
                <form method="post" action="{{ route('profile.destroy') }}" class="stack">
                    @csrf
                    @method('delete')

                    <div>
                        <h2 class="section-title">Tem certeza de que deseja excluir sua conta?</h2>
                        <p class="section-text">
                            Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Digite sua senha para confirmar que deseja excluir permanentemente sua conta.
                        </p>
                    </div>

                    <div>
                        <x-input-label for="password" value="Senha" class="sr-only" />
                        <x-text-input id="password" name="password" type="password" placeholder="Senha" />
                    </div>

                    <div class="modal-actions">
                        <a class="btn btn-ghost" href="#">Cancelar</a>
                        <x-danger-button>Excluir Conta</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>