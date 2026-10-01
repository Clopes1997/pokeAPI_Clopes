@props(['disabled' => false])

@php
    $isPassword = $attributes->get('type') === 'password';
@endphp

@if($isPassword)
<div class="password-input-wrapper">
    <input 
        @disabled($disabled) 
        {{ $attributes->merge(['class' => 'input']) }}
        oninvalid="this.setCustomValidity(
            this.validity.valueMissing ? 'Por favor, preencha este campo.' :
            this.validity.typeMismatch && this.type === 'email' ? 'Por favor, informe um endereço de e-mail válido.' :
            this.validity.tooShort ? 'Este campo precisa ter pelo menos ' + this.minLength + ' caracteres.' :
            this.validity.tooLong ? 'Este campo não pode ter mais de ' + this.maxLength + ' caracteres.' :
            this.validity.patternMismatch ? 'Por favor, siga o formato solicitado.' :
            ''
        )"
        oninput="this.setCustomValidity('')"
    >
    <button type="button" class="password-toggle" onclick="togglePasswordVisibility(this)">
        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path class="eye-open" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle class="eye-open" cx="12" cy="12" r="3"></circle>
            <path class="eye-closed" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" style="display: none;"></path>
            <line class="eye-closed" x1="1" y1="1" x2="23" y2="23" style="display: none;"></line>
        </svg>
    </button>
</div>

<style>
    .password-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .password-input-wrapper input {
        width: 100%;
        padding-right: 2.75rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted-foreground);
        transition: color 0.2s ease;
        border-radius: 0.25rem;
    }
    
    .password-toggle:hover {
        color: var(--foreground);
    }
    
    .password-toggle:focus {
        outline: 2px solid var(--pokemon-red);
        outline-offset: 2px;
    }
    
    .eye-icon {
        width: 20px;
        height: 20px;
    }
</style>

<script>
    function togglePasswordVisibility(button) {
        const wrapper = button.closest('.password-input-wrapper');
        const input = wrapper.querySelector('input');
        const eyeOpen = button.querySelectorAll('.eye-open');
        const eyeClosed = button.querySelectorAll('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.forEach(el => el.style.display = 'none');
            eyeClosed.forEach(el => el.style.display = 'block');
        } else {
            input.type = 'password';
            eyeOpen.forEach(el => el.style.display = 'block');
            eyeClosed.forEach(el => el.style.display = 'none');
        }
    }
</script>
@else
<input 
    @disabled($disabled) 
    {{ $attributes->merge(['class' => 'input']) }}
    oninvalid="this.setCustomValidity(
        this.validity.valueMissing ? 'Por favor, preencha este campo.' :
        this.validity.typeMismatch && this.type === 'email' ? 'Por favor, informe um endereço de e-mail válido.' :
        this.validity.tooShort ? 'Este campo precisa ter pelo menos ' + this.minLength + ' caracteres.' :
        this.validity.tooLong ? 'Este campo não pode ter mais de ' + this.maxLength + ' caracteres.' :
        this.validity.patternMismatch ? 'Por favor, siga o formato solicitado.' :
        ''
    )"
    oninput="this.setCustomValidity('')"
>
@endif