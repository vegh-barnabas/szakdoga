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
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/fontawesome.all.min.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('index') }}">
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
              <a class="nav-link {{ Route::is('guest.tickets') ? 'active' : '' }}"
                href="{{ route('guest.tickets') }}">Jegyeim</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('guest.buy-ticket') ? 'active' : '' }}"
                href="{{ route('guest.buy-ticket') }}">Jegyvásárlás</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('guest.statistics') ? 'active' : '' }}"
                href="{{ route('guest.statistics') }}">Statisztika</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                aria-expanded="true">{{ Auth::user()->name }}</a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                <li>
                  <a class="dropdown-item" onclick="event.preventDefault()" style="pointer-events: none;">Kredit:
                    <b>{{ Auth::user()->credits }}</b></a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('guest.settings') }}">Beállítások</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('sensitive-settings') }}">Szenzitív beállítások</a>
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
