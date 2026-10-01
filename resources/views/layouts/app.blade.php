<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PokéAPI - Clopes'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ui.css') }}">
    <style>
        .toast-container {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 400px;
        }
        .toast {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3);
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .toast.toast-success {
            border-left: 4px solid hsl(142, 76%, 36%);
            background: hsl(142, 76%, 98%);
        }
        .toast.toast-error {
            border-left: 4px solid var(--pokemon-red);
            background: hsl(0, 88%, 98%);
        }
        .toast.toast-info {
            border-left: 4px solid hsl(217, 91%, 60%);
            background: hsl(217, 91%, 98%);
        }
        .toast-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .toast-content {
            flex: 1;
        }
        .toast-title {
            font-weight: 800;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }
        .toast-message {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--muted-foreground);
        }
        .toast-success .toast-title { color: hsl(142, 76%, 20%); }
        .toast-error .toast-title { color: hsl(0, 88%, 30%); }
        .toast-info .toast-title { color: hsl(217, 91%, 30%); }
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        @media (max-width: 640px) {
            .toast-container {
                left: 1rem;
                right: 1rem;
                max-width: none;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    @include('layouts.navigation')

    @isset($header)
        <div class="page-header">
            <div class="container">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="page-content">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        // Sistema de Toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: '✓',
                error: '⚠️',
                info: 'ℹ️'
            };
            
            const titles = {
                success: 'Sucesso',
                error: 'Atenção',
                info: 'Informação'
            };
            
            const icon = icons[type] || icons.info;
            const title = titles[type] || titles.info;
            
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Remove após 5 segundos
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Expor globalmente
        window.showToast = showToast;

        // Função para traduzir e mostrar erros
        function translateAndShowError(error) {
            const translations = {
                'auth.failed': 'As credenciais fornecidas estão incorretas.',
                'auth.password': 'A senha está incorreta.',
                'validation.required': 'Este campo é obrigatório.',
                'validation.email': 'Por favor, informe um endereço de e-mail válido.',
                'validation.confirmed': 'A confirmação de senha não confere.',
                'validation.min.string': 'Este campo precisa ter mais caracteres.',
                'validation.min': 'Este campo precisa ter mais caracteres.',
                'validation.max.string': 'Este campo não pode ter mais caracteres que o permitido.',
                'validation.unique': 'Este valor já está em uso.',
                'validation.exists': 'O valor selecionado é inválido.',
                'validation.string': 'Este campo deve ser um texto válido.',
                'validation.current_password': 'A senha atual está incorreta.',
            };
            
            let translatedError = error;
            
            // Tenta traduzir por chave exata
            for (const [key, translation] of Object.entries(translations)) {
                if (error.includes(key)) {
                    translatedError = translation;
                    break;
                }
            }
            
            // Se não traduziu, tenta padrões em inglês
            if (translatedError === error) {
                if (/The .+ field is required\.?/i.test(error)) {
                    translatedError = 'Este campo é obrigatório.';
                } else if (/The .+ must be at least (\d+) characters\.?/i.test(error)) {
                    const match = error.match(/The .+ must be at least (\d+) characters\.?/i);
                    translatedError = `Este campo precisa ter pelo menos ${match[1]} caracteres.`;
                } else if (/The .+ has already been taken\.?/i.test(error)) {
                    translatedError = 'Este valor já está em uso.';
                } else if (/The .+ must be a valid email address\.?/i.test(error)) {
                    translatedError = 'Por favor, informe um endereço de e-mail válido.';
                } else if (/The .+ confirmation does not match\.?/i.test(error) || /The .+ field confirmation does not match\.?/i.test(error)) {
                    translatedError = 'A confirmação de senha não confere.';
                } else if (/The provided password was incorrect\.?/i.test(error)) {
                    translatedError = 'A senha fornecida está incorreta.';
                }
            }
            
            showToast(translatedError, 'error');
        }

        // Mostrar erros de validação em toast (bag padrão)
        @if ($errors->default->any())
            @foreach ($errors->default->all() as $error)
                translateAndShowError('{{ addslashes($error) }}');
            @endforeach
        @endif

        // Mostrar erros do formulário de atualização de senha
        @if ($errors->updatePassword->any())
            @foreach ($errors->updatePassword->all() as $error)
                translateAndShowError('{{ addslashes($error) }}');
            @endforeach
        @endif

        // Mostrar erros do formulário de exclusão de usuário
        @if ($errors->userDeletion->any())
            @foreach ($errors->userDeletion->all() as $error)
                translateAndShowError('{{ addslashes($error) }}');
            @endforeach
        @endif

        // Mostrar mensagens de sucesso em toast
        @if (session('success'))
            showToast('{{ addslashes(session('success')) }}', 'success');
        @endif

        @if (session('error'))
            showToast('{{ addslashes(session('error')) }}', 'error');
        @endif

        @if (session('info'))
            showToast('{{ addslashes(session('info')) }}', 'info');
        @endif

        @if (session('status'))
            @php
                $statusTranslations = [
                    'profile-updated' => 'Perfil atualizado com sucesso!',
                    'password-updated' => 'Senha atualizada com sucesso!',
                    'profile-information-updated' => 'Informações do perfil atualizadas!',
                    'verification-link-sent' => 'Um novo link de verificação foi enviado para seu e-mail.',
                ];
                $translatedStatus = session('status');
                if (isset($statusTranslations[session('status')])) {
                    $translatedStatus = $statusTranslations[session('status')];
                }
            @endphp
            showToast('{{ addslashes($translatedStatus) }}', 'success');
        @endif
    </script>
</body>
</html>