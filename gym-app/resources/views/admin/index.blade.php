@extends('layouts.admin')
@section('title', 'Főoldal')

@section('content')
  <h2>Üdv újra, <b>admin1</b>!</h2>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Legutóbb megvásárolt 5 bérlet</h5>
                <h6 class="card-subtitle mb-2 text-muted">
                  {{ $gym_name }}
                </h6>
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Edzőterem</th>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($monthly_tickets as $ticket)
                      <tr>
                        <td><b>{{ $ticket->user->name }}</b></td>
                        <td>{{ $ticket->gym->name }}</td>
                        <td>{{ $ticket->type->name }}</td>
                        <td>{{ $ticket->expiration }}</td>
                        @if ($ticket->useable())
                          <td>Felhasználható</td>
                        @else
                          <td>Lejárt</td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Legutóbb megvásárolt 5 jegy</h5>
                <h6 class="card-subtitle mb-2 text-muted">
                  {{ $gym_name }}
                </h6>
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Edzőterem</th>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket)
                      <tr>
                        <td><b>{{ $ticket->user->name }}</b></td>
                        <td>{{ $ticket->gym->name }}</td>
                        <td>{{ $ticket->type->name }}</td>
                        <td>{{ $ticket->expiration }}</td>
                        @if ($ticket->useable())
                          <td>Felhasználható</td>
                        @elseif($ticket->used())
                          <td>Felhasznált</td>
                        @else
                          <td>Lejárt</td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col p-3 align-self-center">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Státusz</h5>
          <h6 class="card-subtitle mb-2 text-muted">
            {{ $gym_name }}
          </h6>
          <p class="card-text">
            <!-- ikon -->
          <h1>Belépett vendégek: <b class="text-success">{{ $active_enterances->count() }}</b></h1>
          @if ($active_enterances->count() > 0)
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Név</th>
                  <th scope="col">Edzőterem</th>
                  <th scope="col">Felhasznált bérlet/jegy</th>
                  <th scope="col">Belépés időpontja</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($active_enterances as $enterance)
                  <tr>
                    <td><b>{{ $enterance->user->name }}</b></td>
                    <td><b>{{ $enterance->gym->name }}</b></td>
                    <td><b>{{ $enterance->ticket->type->name }}</b></td>
                    <td><b>{{ $enterance->enter }}</b></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
          @endif
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
