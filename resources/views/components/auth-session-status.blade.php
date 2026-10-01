@props(['status'])

@php
    // Traduções de mensagens de status comuns
    $statusTranslations = [
        'passwords.sent' => 'Se o e-mail constar na nossa base, você receberá uma mensagem com o link de redefinição.',
        'passwords.reset' => 'Sua senha foi redefinida com sucesso!',
        'verification.sent' => 'Um novo link de verificação foi enviado para seu e-mail.',
    ];
    
    $translatedStatus = $status;
    if ($status && isset($statusTranslations[$status])) {
        $translatedStatus = $statusTranslations[$status];
    }
@endphp

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success']) }}>
        {{ $translatedStatus }}
    </div>
@endif