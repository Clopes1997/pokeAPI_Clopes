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
    <div class="auth-page">
        <div class="auth-top">
            <a href="{{ url('/') }}" class="brand">
                <div class="pokeball pokeball-md">
                    <div class="pokeball-top"></div>
                    <div class="pokeball-bottom"></div>
                    <div class="pokeball-band"></div>
                    <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
                </div>
                <span class="brand-text">Poké<span>API</span></span>
            </a>
        </div>

        <div class="auth-card card">
            {{ $slot }}
        </div>
    </div>
    <div class="toast-container" id="toast-container"></div>

    <script>
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const safeType = ['success', 'error', 'info'].includes(type) ? type : 'info';
            toast.className = `toast toast-${safeType}`;

            const icons = { success: '✓', error: '⚠️', info: 'ℹ️' };
            const titles = { success: 'Sucesso', error: 'Atenção', info: 'Informação' };

            const icon = icons[safeType];
            const title = titles[safeType];

            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @php
                    $translations = [
                        'auth.failed' => 'As credenciais fornecidas estão incorretas.',
                        'auth.password' => 'A senha está incorreta.',
                        'passwords.user' => 'Se o e-mail constar na nossa base, você receberá uma mensagem com o link de redefinição.',
                        'passwords.throttled' => 'Aguarde alguns instantes antes de tentar novamente.',
                        'passwords.token' => 'Este link de redefinição de senha é inválido ou expirou.',
                        'passwords.password' => 'A senha deve ter pelo menos 8 caracteres.',
                        'validation.required' => 'Este campo é obrigatório.',
                        'validation.email' => 'Por favor, informe um endereço de e-mail válido.',
                        'validation.confirmed' => 'A confirmação de senha não confere.',
                        'validation.min.string' => 'Este campo precisa ter mais caracteres.',
                        'validation.min' => 'Este campo precisa ter mais caracteres.',
                        'validation.max.string' => 'Este campo não pode ter mais caracteres que o permitido.',
                        'validation.unique' => 'Este valor já está em uso.',
                        'validation.exists' => 'O valor selecionado é inválido.',
                        'validation.string' => 'Este campo deve ser um texto válido.',
                        'validation.current_password' => 'A senha atual está incorreta.',
                    ];
                    
                    $translatedError = $error;
                    $toastType = 'error';
                    
                    foreach ($translations as $key => $translation) {
                        if (str_contains($error, $key)) {
                            $translatedError = $translation;
                            if ($key === 'passwords.user') {
                                $toastType = 'info';
                            }
                            break;
                        }
                    }
                    
                    
                    if ($translatedError === $error) {
                        if (preg_match('/The .+ field is required\.?/i', $error)) {
                            $translatedError = 'Este campo é obrigatório.';
                        } elseif (preg_match('/The .+ must be at least (\d+) characters\.?/i', $error, $matches)) {
                            $translatedError = 'Este campo precisa ter pelo menos ' . $matches[1] . ' caracteres.';
                        } elseif (preg_match('/The .+ has already been taken\.?/i', $error)) {
                            $translatedError = 'Este valor já está em uso.';
                        } elseif (preg_match('/The .+ must be a valid email address\.?/i', $error)) {
                            $translatedError = 'Por favor, informe um endereço de e-mail válido.';
                        } elseif (preg_match('/The .+ confirmation does not match\.?/i', $error)) {
                            $translatedError = 'A confirmação de senha não confere.';
                        } elseif (preg_match('/The .+ field confirmation does not match\.?/i', $error)) {
                            $translatedError = 'A confirmação de senha não confere.';
                        } elseif (preg_match('/The provided password was incorrect\.?/i', $error)) {
                            $translatedError = 'A senha fornecida está incorreta.';
                        } elseif (preg_match('/These credentials do not match our records\.?/i', $error)) {
                            $translatedError = 'As credenciais fornecidas não correspondem aos nossos registros.';
                        }
                    }
                @endphp
                showToast('{{ addslashes($translatedError) }}', '{{ $toastType }}');
            @endforeach
        @endif

        @if (session('status'))
            @php
                $statusTranslations = [
                    'passwords.sent' => 'Se o e-mail constar na nossa base, você receberá uma mensagem com o link de redefinição.',
                    'passwords.reset' => 'Sua senha foi redefinida com sucesso!',
                ];
                $translatedStatus = session('status');
                $toastType = 'success';
                if (isset($statusTranslations[session('status')])) {
                    $translatedStatus = $statusTranslations[session('status')];
                    if (session('status') === 'passwords.sent') {
                        $toastType = 'info';
                    }
                }
            @endphp
            showToast('{{ addslashes($translatedStatus) }}', '{{ $toastType }}');
        @endif
    </script>
</body>
</html>