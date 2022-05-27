@extends('layouts.receptionist')
@section('title', 'Megvásárolt jegyek')

@section('content')
  <h2 class="mb-3">Megvásárolt jegyek</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym_name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Név</th>
                <th>Típus</th>
                <th>Leírás</th>
                <th>Felhasználó</th>
                <th>Státusz</th>
                <th>Kód</th>
                <th>Megvásárolva</th>
                <th>Lejárat</th>
                <th>Felhasználva</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tickets as $ticket)
                <tr>
                  <td>{{ $ticket->buyable_ticket->name }}</td>
                  <td class="text-success"><b>{{ $ticket->get_type() }}</b></td>
                  <td>{{ $ticket->buyable_ticket->description }}</td>
                  <td>{{ $ticket->user->name }} (ID {{ $ticket->user->id }})</td>
                  @if ($ticket->useable())
                    <td class="text-success">Felhasználható</td>
                  @elseif($ticket->used())
                    <td class="text-danger">Felhasznált</td>
                  @else
                    <td class="text-warning">Lejárt</td>
                  @endif
                  <td>{{ $ticket->code }}</td>
                  <td>{{ $ticket->bought() }}</td>
                  <td>{{ $ticket->expiration() }}</td>
                  @if ($ticket->used())
                    <td>{{ $ticket->use_date() }}</td>
                  @else
                    <td></td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center">
            {{ $tickets->links() }}
          </div>
        </div>
      </div>
    </div>
  @endsection
