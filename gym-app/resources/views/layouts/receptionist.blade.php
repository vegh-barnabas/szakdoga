<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
    @if (View::hasSection('title'))
      @yield('title') |
    @endif
    {{ config('app.name', 'Edzőterem') }}
  </title>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/fontawesome.all.min.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" id="receptionist-navbar">
      <div class="container-fluid">
        <a class="navbar-brand text-success" href="{{ route('index') }}">
          Beléptető rendszer
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
          aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link {{ Route::is('index') ? 'active' : '' }}" href="{{ route('index') }}">Főoldal</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('receptionist.let-in.index-page') ? 'active' : '' }}"
                href="{{ route('receptionist.let-in.index-page') }}">Beléptetés</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('receptionist.let-out.index-page') ? 'active' : '' }}"
                href="{{ route('receptionist.let-out.index-page') }}">Kiléptetés</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('receptionist.add-credits.index') ? 'active' : '' }}"
                href="{{ route('receptionist.add-credits.index') }}">Kredit feltöltése</a>
            </li>
            <li class="nav-item dropend">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Statisztika
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                <li><a class="dropdown-item" href="{{ route('purchased-monthly') }}">Megvásárolt bérletek
                    listája</a></li>
                <li><a class="dropdown-item" href="{{ route('purchased-tickets') }}">Megvásárolt jegyek
                    listája</a></li>
                <li><a class="dropdown-item" href="{{ route('buyable-tickets.index') }}">Megvásárolható
                    jegyek/bérletek listája</a></li>
                <li><a class="dropdown-item" href="{{ route('receptionist.entered-users') }}">Belépett
                    vendégek</a>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                aria-expanded="true">{{ Auth::user()->name }}</a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                <li>
                  <a class="dropdown-item" href="{{ route('sensitive-settings') }}">Beállítások</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Kilépés
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </li>
            </li>
          </ul>
          </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container mt-4">
      @yield('content')
    </main>

    @yield('scripts')
  </div>
</body>

</html>
