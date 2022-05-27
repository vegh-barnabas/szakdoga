@extends('layouts.receptionist')
@section('title', 'Főoldal')

@section('content')
  <h2>Üdv újra, <b>{{ Auth::user()->name }}</b>!</h2>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5>Legutóbb megvásárolt 5 bérlet</h5>
              <h6 class="card-subtitle text-light mb-2">
                {{ $gym->name }}
              </h6>
            </div>
            <div class="card-body">
              <p class="card-text">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Név</th>
                      <th scope="col">Vásárlás dátuma</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($monthly_tickets as $ticket)
                      <tr>
                        <td><b>{{ $ticket->user->name }}</b></td>
                        <td>{{ $ticket->buyable_ticket->name }}</td>
                        <td>{{ $ticket->bought() }}</td>
                        <td>{{ $ticket->expiration() }}</td>
                        @if ($ticket->expired())
                          <td class="text-danger">Lejárt</td>
                        @elseif ($ticket->useable())
                          <td class="text-success">Érvényes</td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              </p>
              <a href={{ route('purchased-monthly') }} class="card-link">többi bérlet megjelenítése</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div class="card">
            <div class="card-header bg-success text-white">
              <h5>Legutóbb megvásárolt 5 jegy</h5>
              <h6 class="card-subtitle text-light mb-2">
                {{ $gym->name }}
              </h6>
            </div>
            <div class="card-body">
              <p class="card-text">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Név</th>
                      <th scope="col">Vásárlás dátuma</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket)
                      <tr>
                        <td><b>{{ $ticket->user->name }}</b></td>
                        <td>{{ $ticket->buyable_ticket->name }}</td>
                        <td>{{ $ticket->bought() }}</td>
                        <td>{{ $ticket->expiration() }}</td>
                        @if ($ticket->expired())
                          <td class="text-danger">Lejárt</td>
                        @elseif ($ticket->used())
                          <td class="text-warning">Felhasznált</td>
                        @elseif ($ticket->useable())
                          <td class="text-success">Érvényes</td>
                        @endif
                      </tr>
                    @endforeach
                </table>
                </p>
                <a href={{ route('purchased-tickets') }} class="card-link">többi jegy megjelenítése</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col p-3">
      <div class="card">
        <div class="card-header">
          <h5>Státusz</h5>
          <h6 class="card-subtitle">
            {{ $gym->name }}
          </h6>
        </div>
        <div class="card-body">
          <p class="card-text">
          <h1>Belépett vendégek: <b class="text-success">{{ $enterances->count() }}</b></h1>
          <h2>Legutóbb belépett 5 vendég:</h2>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Vendég neve</th>
                  <th scope="col">Felhasznált bérlet/jegy</th>
                  <th scope="col">Öltözőszekrény száma</th>
                  <th scope="col">Belépés időpontja</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($enterances as $enterance)
                  <tr>
                    <td><b>{{ $enterance->user->name }}</b></td>
                    <td>{{ $enterance->ticket->buyable_ticket->name }}</td>
                    <td>
                      {{ $enterance->locker->number }}
                      ({{ $enterance->locker->gender == 'male' ? 'férfi' : 'női' }} öltöző)
                    </td>
                    <td>{{ $enterance->enter() }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            </p>
            <a href="{{ route('receptionist.entered-users') }}" class="card-link">
              többi belépett vendég megjelenítése
            </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
