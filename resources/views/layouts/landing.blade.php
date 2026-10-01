<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PokéAPI - Clopes</title>
  <meta name="description" content="Acesse informações detalhadas sobre Pokémon, movimentos, habilidades e muito mais.">

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/ui.css') }}">
</head>

<body>
  <header class="header">
    <div class="container header-inner">
      <div class="brand">
        <div class="pokeball pokeball-sm">
          <div class="pokeball-top"></div>
          <div class="pokeball-bottom"></div>
          <div class="pokeball-band"></div>
          <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
        </div>
        <span class="brand-text">Poké<span>API</span></span>
      </div>
      <div class="nav-actions">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-ghost">Painel</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-ghost">Entrar</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary">Criar conta</a>
          @endif
        @endauth
      </div>
    </div>
  </header>

  @yield('content')

  <footer class="footer">
    <div class="container footer-inner">
      <div class="footer-logo">
        <div class="pokeball pokeball-sm">
          <div class="pokeball-top"></div>
          <div class="pokeball-bottom"></div>
          <div class="pokeball-band"></div>
          <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
        </div>
        <span class="brand-text">Poké<span>API</span></span>
      </div>
      <p>Clopes Code</p>
    </div>
  </footer>
</body>
</html>
