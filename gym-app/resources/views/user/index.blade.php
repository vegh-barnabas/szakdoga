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
                @if ($monthly_tickets->isEmpty())
                  <h5>Nincs felhasználható bérleted!</h5>
                @else
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Név</th>
                          <th scope="col">Lejárat</th>
                          <th scope="col">Státusz</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($monthly_tickets as $ticket)
                          <tr>
                            <td>{{ $ticket->buyable_ticket->name }}</td>
                            <td>{{ $ticket->expiration() }}</td>
                            @if ($ticket->expired())
                              <td class="text-danger">Lejárt</td>
                              <td><button type="button" class="btn btn-light">Hosszabbítás</button></td>
                            @else
                              <td class="text-success">Aktív</td>
                              <td>
                                <p>
                                  <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="{{ '#' . Str::slug($ticket->buyable_ticket->name . $ticket->id) }}"
                                    aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                    Belépési kód
                                  </button>
                                </p>
                                <div class="collapse"
                                  id="{{ Str::slug($ticket->buyable_ticket->name . $ticket->id) }}">
                                  <div class="card card-body">
                                    {{ $ticket->code }}
                                  </div>
                                </div>
                              </td>
                            @endif
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @endif
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
                @if ($tickets->isEmpty())
                  <h5>Nincs felhasználható jegyed!</h5>
                @else
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Név</th>
                          <th scope="col">Lejárat</th>
                          <th scope="col">Státusz</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tickets as $ticket)
                          <tr>
                            <td>{{ $ticket->buyable_ticket->name }}</td>
                            <td>{{ $ticket->expiration() }}</td>
                            @if ($ticket->used() == true)
                              <td class="text-warning">Felhasznált</td>
                              <td><button type="button" class="btn btn-light">Új vásárlása</button></td>
                            @elseif ($ticket->expired())
                              <td class="text-danger">Lejárt</td>
                              <td><button type="button" class="btn btn-light">Új vásárlása</button></td>
                            @else
                              <td class="text-success">Felhasználható</td>
                              <td>
                                <p>
                                  <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                                    data-bs-target="{{ '#' . Str::slug($ticket->buyable_ticket->name . $ticket->id) }}"
                                    aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                    Belépési kód
                                  </button>
                                </p>
                                <div class="collapse"
                                  id="{{ Str::slug($ticket->buyable_ticket->name . $ticket->id) }}">
                                  <div class="card card-body">
                                    {{ $ticket->code }}
                                  </div>
                                </div>
                              </td>
                            @endif
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @endempty
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col p-3">
    <div class="card">
      <div class="card-header">
        <h5>Státusz</h5>
        <h6 class="card-subtitle text-muted mb-2">
          {{ $gym->name }}
        </h6>
      </div>
      <div class="card-body">
        @if ($last_enterance_data == null)
          <p class="card-text">
          <h1 class="text-danger">Kilépve</h1>
          <h4 class="text-muted">Utolsó belépés időtartama:</h4>
          <h5 class="text-muted">Nincs</h5>
          </p>
        @elseif ($last_enterance_data['last_enterance']->exited())
          <p class="card-text">
          <h1 class="text-danger">Kilépve</h1>
          <h4 class="text-muted">Utolsó belépés időtartama:</h4>
          <h5 class="text-muted">
            {{ date_create($last_enterance_data['last_enterance']->enter)->format('Y. m. d. H:i') }} -
            {{ date_create($last_enterance_data['last_enterance']->exit)->format('H:i') }}
            ({{ $last_enterance_data['dur_hours'] }} óra {{ $last_enterance_data['dur_minutes'] }} perc)</h5>
          </p>
        @else
          <p class="card-text">
          <h1 class="text-success">Belépve</h1>
          <h3>{{ $last_enterance_data['last_enterance']->enter }}</h3>
          <button class="btn btn-danger" type="button" data-bs-toggle="collapse"
            data-bs-target={{ '#' . Auth::user()->exit_code }} aria-expanded="false"
            aria-controls="{{ Auth::user()->exit_code }}">
            Kilépési kód
          </button>
          </p>
          <div class="collapse" id="{{ Auth::user()->exit_code }}">
            <div class="card card-body">
              {{ Auth::user()->exit_code }}
            </div>
          </div>
          </p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
