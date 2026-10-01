<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Gerenciar Usuários</h2>
    </x-slot>

    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nome</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Função Atual</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Alterar Função</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">{{ $user->name }}</td>
                        <td style="padding: 1rem;">{{ $user->email }}</td>
                        <td style="padding: 1rem;">
                            @if($user->roles->count() > 0)
                                <span class="pill">{{ $user->roles->first()->display_name }}</span>
                            @else
                                <span style="color: #6b7280;">Sem função</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <form method="POST" action="{{ route('admin.users.update-role', $user->id) }}" style="display: flex; gap: 0.5rem; align-items: center;">
                                @csrf
                                @method('PATCH')
                                <select name="role_id" class="input" style="flex: 1; min-width: 150px;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->roles->first()?->id === $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>
