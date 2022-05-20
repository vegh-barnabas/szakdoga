@extends('layouts.receptionist')
@section('title', 'Megvásárolt bérletek')

@section('content')
  <h2 class="mb-3">Megvásárolt bérletek</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym_name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <table class="table">
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
            </tr>
          </thead>
          <tbody>
            @foreach ($tickets as $ticket)
              <tr>
                <td>{{ $ticket->type->name }}</td>
                <td>{{ $ticket->get_type() }}</td>
                <td>{{ $ticket->type->description }}</td>
                <td>{{ $ticket->user->name }} (ID {{ $ticket->user->id }})</td>
                @if ($ticket->useable())
                  <td>Felhasználható</td>
                @elseif($ticket->used())
                  <td>Felhasznált</td>
                @endif
                <td>{{ $ticket->code }}</td>
                <td>{{ $ticket->bought }}</td>
                <td>{{ $ticket->expiration }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endsection
