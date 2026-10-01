<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dados inválidos</title>
    <link rel="stylesheet" href="{{ asset('css/ui.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 50%,hsla(0,88%,51%,.05) 0%,transparent 50%),radial-gradient(circle at 80% 80%,hsla(0,0%,0%,.02) 0%,transparent 50%);
        }
        .toast {
            display: flex;
            gap: .75rem;
            align-items: flex-start;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,.3);
            background: hsl(217, 91%, 98%);
            border-left: 4px solid hsl(217, 91%, 60%);
            max-width: 520px;
        }
        .toast-icon { font-size: 1.25rem; flex-shrink: 0; }
        .toast-title { font-weight: 800; font-size: 0.95rem; color: hsl(217, 91%, 30%); }
        .toast-message { font-size: 0.9rem; font-weight: 600; color: var(--muted-foreground); }
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;}
    </style>
</head>
<body>
    <div class="toast">
        <div class="toast-icon">ℹ️</div>
        <div class="toast-content">
            <div class="toast-title">Informação</div>
            <div class="toast-message">
                Não foi possível processar os dados enviados. Verifique os campos e tente novamente.
            </div>
        </div>
    </div>
    <script>
        (function () {
            const key = 'pokeapi-last-validation-back';
            const last = Number(sessionStorage.getItem(key) || 0);
            const now = Date.now();
            const tooRecent = now - last < 2000;

            if (!tooRecent && window.history.length > 1) {
                sessionStorage.setItem(key, String(now));
                setTimeout(() => window.history.back(), 150);
            }
        }());
    </script>
</body>
</html>

