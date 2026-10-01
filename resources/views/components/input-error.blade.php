@props(['messages'])

@php
    // Traduções de mensagens de validação comuns
    $translations = [
        'auth.failed' => 'As credenciais fornecidas estão incorretas.',
        'auth.password' => 'A senha está incorreta.',
        'auth.throttle' => 'Muitas tentativas de login. Tente novamente em :seconds segundos.',
        'passwords.reset' => 'Sua senha foi redefinida com sucesso!',
        'passwords.sent' => 'Enviamos por e-mail o link de redefinição de senha!',
        'passwords.throttled' => 'Por favor, aguarde antes de tentar novamente.',
        'passwords.token' => 'Este link de redefinição de senha é inválido ou expirou.',
        'passwords.user' => 'Se o e-mail constar na nossa base, você receberá uma mensagem com o link de redefinição.',
        'passwords.password' => 'A senha deve ter pelo menos 8 caracteres.',
        'passwords.throttled' => 'Aguarde alguns instantes antes de tentar novamente.',
        'validation.accepted' => 'Este campo precisa ser aceito.',
        'validation.confirmed' => 'A confirmação não confere.',
        'validation.current_password' => 'A senha atual está incorreta.',
        'validation.email' => 'Por favor, informe um endereço de e-mail válido.',
        'validation.exists' => 'O valor selecionado é inválido.',
        'validation.max.string' => 'Este campo não pode ter mais de :max caracteres.',
        'validation.min.string' => 'Este campo precisa ter pelo menos :min caracteres.',
        'validation.required' => 'Este campo é obrigatório.',
        'validation.unique' => 'Este valor já está em uso.',
        'validation.same' => 'Os campos devem corresponder.',
        'validation.string' => 'Este campo deve ser um texto válido.',
        'validation.numeric' => 'Este campo deve ser um número.',
        'validation.integer' => 'Este campo deve ser um número inteiro.',
        'validation.url' => 'Por favor, informe uma URL válida.',
        'validation.regex' => 'O formato deste campo é inválido.',
    ];
    
    $translateMessage = function($msg) use ($translations) {
        // Substitui chaves de tradação por mensagens em português
        foreach ($translations as $key => $translation) {
            if (str_contains($msg, $key)) {
                return $translation;
            }
        }
        
        // Traduz padrões específicos em inglês
        if (preg_match('/The .+ field is required\.?/i', $msg)) {
            return 'Este campo é obrigatório.';
        }
        if (preg_match('/The .+ must be at least (\d+) characters\.?/i', $msg, $matches)) {
            return 'Este campo precisa ter pelo menos ' . $matches[1] . ' caracteres.';
        }
        if (preg_match('/The .+ may not be greater than (\d+) characters\.?/i', $msg, $matches)) {
            return 'Este campo não pode ter mais de ' . $matches[1] . ' caracteres.';
        }
        if (preg_match('/The .+ has already been taken\.?/i', $msg)) {
            return 'Este valor já está em uso.';
        }
        if (preg_match('/The .+ must be a valid email address\.?/i', $msg)) {
            return 'Por favor, informe um endereço de e-mail válido.';
        }
        if (preg_match('/The .+ confirmation does not match\.?/i', $msg)) {
            return 'A confirmação não confere.';
        }
        if (preg_match('/The .+ field confirmation does not match\.?/i', $msg)) {
            return 'A confirmação não confere.';
        }
        if (preg_match('/The provided password was incorrect\.?/i', $msg)) {
            return 'A senha fornecida está incorreta.';
        }
        if (preg_match('/These credentials do not match our records\.?/i', $msg)) {
            return 'As credenciais fornecidas não correspondem aos nossos registros.';
        }
        if (preg_match('/passwords\.throttled/i', $msg)) {
            return 'Aguarde alguns instantes antes de tentar novamente.';
        }
        
        return $msg;
    };
@endphp

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'field-error']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $translateMessage($message) }}</li>
        @endforeach
    </ul>
@endif