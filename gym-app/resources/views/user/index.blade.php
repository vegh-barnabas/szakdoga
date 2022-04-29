@extends('layouts.user')
@section('title', 'Főoldal')

@section('content')
  <h2>Üdv újra, <b>{{ Auth::user()->name }}</b>!</h2>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Felhasználható bérletek</h5>
                    <h6 class="card-subtitle text-light mb-2">
                      {{ $gym->name }}
                    </h6>
                </div>
              <div class="card-body">
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                      <th scope="col">Lehetőség</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket) {{-- TODO: if no ticket then write something in forelse --}}
                      @if ($ticket->type->type == 'bérlet')
                        <tr>
                          <td>{{ $ticket->type->name }}</td>
                          <td>{{ $ticket->expiration }}</td>
                          @if ($ticket->expiration < date('Y-m-d H:i:s'))
                            <td class="text-danger">Lejárt</td>
                            <td><button type="button" class="btn btn-light">Hosszabbítás</button></td>
                          @else
                            <td class="text-success">Aktív</td>
                            <td>
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target={{ "#" . $ticket->type->name . $ticket->id }} aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                  Belépési kód
                                </button>
                              </p>
                              <div class="collapse" id="{{ $ticket->type->name . $ticket->id }}">
                                <div class="card card-body">
                                  {{ $ticket->code }}
                                </div>
                              </div>
                            </td>
                          @endif
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
                </p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>Felhasználható jegyek</h5>
                    <h6 class="card-subtitle text-light mb-2">
                      {{ $gym->name }}
                    </h6>
                </div>
              <div class="card-body">
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($tickets as $ticket)
                      @if ($ticket->type->type == 'jegy')
                        <tr>
                          <td>{{ $ticket->type->name }}</td>
                          <td>{{ $ticket->expiration }}</td>
                          @if ($ticket->used() == true)
                            <td class="text-warning">Felhasznált</td>
                            <td><button type="button" class="btn btn-light">Új vásárlása</button></td>
                          @elseif ($ticket->expiration < date('Y-m-d H:i:s'))
                            <td class="text-danger">Lejárt</td>
                            <td><button type="button" class="btn btn-light">Új vásárlása</button></td>
                          @else
                            <td class="text-success">Felhasználható</td>
                            <td>
                                <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target={{ "#" . $ticket->type->name . $ticket->id }} aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                  Belépési kód
                                </button>
                              </p>
                              <div class="collapse" id="{{ $ticket->type->name . $ticket->id }}">
                                <div class="card card-body">
                                  {{ $ticket->code }}
                                </div>
                              </div>
                            </td>
                            <td></td>
                          @endif
                        </tr>
                      @endif
                    @empty
                      Nincs bérleted
                    @endforelse
                  </tbody>
                </table>
                </p>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col align-self-center p-3">
      <div class="card">
        <div class="card-header">
            <h5>Státusz</h5>
            <h6 class="card-subtitle text-muted mb-2">
              {{ $gym->name }}
            </h6>
        </div>
        <div class="card-body">
            @if ($last_enterance->exited())
            <p class="card-text">
              <!-- ikon -->
            <h1 class="text-danger">Kilépve</h1>
            <h4 class="text-muted">Utolsó belépés időtartama:</h4>
            <h5 class="text-muted">{{ date_create($last_enterance->enter)->format('Y. m. d. H:i') }} -
              {{ date_create($last_enterance->exit)->format('H:i') }}
              ({{ $dur_hours }} óra {{ $dur_minutes }} perc)</h5>
            </p>
          @else
            <p class="card-text">
              <!-- ikon -->
            <h1 class="text-success">Belépve</h1>
            <h3>{{ $last_enterance->enter }}</h3>
            <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target={{ "#" . Auth::user()->exit_code }} aria-expanded="false" aria-controls="{{ Auth::user()->exit_code }}">
              Kilépési kód
            </button>
          </p>
          <div class="collapse" id="{{ Auth::user()->exit_code }}">
            <div class="card card-body">
              {{ $ticket->code }}
            </div>
          </div>
            </p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
