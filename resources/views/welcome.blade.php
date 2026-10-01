@extends('layouts.landing')

@section('content')
  <section class="hero">
    <div class="hero-decoration hero-decoration-1">
      <div class="pokeball pokeball-xl">
        <div class="pokeball-top"></div>
        <div class="pokeball-bottom"></div>
        <div class="pokeball-band"></div>
        <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
      </div>
    </div>
    <div class="hero-decoration hero-decoration-2">
      <div class="pokeball pokeball-lg">
        <div class="pokeball-top"></div>
        <div class="pokeball-bottom"></div>
        <div class="pokeball-band"></div>
        <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
      </div>
    </div>
    <div class="hero-decoration hero-decoration-3">
      <div class="pokeball pokeball-xl">
        <div class="pokeball-top"></div>
        <div class="pokeball-bottom"></div>
        <div class="pokeball-band"></div>
        <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
      </div>
    </div>

    <div class="container hero-content">
      <h1 class="hero-title">
        Todos os dados<br>
        <span class="red-text">Pokémon</span>
      </h1>

      <p class="hero-subtitle">
        Acesse informações detalhadas sobre Pokémon, movimentos, habilidades e muito mais através de uma API completa e moderna.
      </p>
    </div>
  </section>

  <section class="cta">
    <div class="container">
      <div class="cta-box">
        <div class="cta-decoration cta-decoration-1">
          <div class="pokeball pokeball-xl">
            <div class="pokeball-top"></div>
            <div class="pokeball-bottom"></div>
            <div class="pokeball-band"></div>
            <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
          </div>
                </div>
        <div class="cta-decoration cta-decoration-2">
          <div class="pokeball pokeball-lg">
            <div class="pokeball-top"></div>
            <div class="pokeball-bottom"></div>
            <div class="pokeball-band"></div>
            <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
                </div>
        </div>

        <div class="cta-content">
          <h2 class="cta-title">Pronto para começar?</h2>
          <p class="cta-text">Temos que pegar todos!</p>
          @auth
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Painel</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-secondary">Entrar</a>
          @endauth
        </div>
      </div>
    </div>
  </section>
@endsection