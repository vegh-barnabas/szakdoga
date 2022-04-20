@extends('layouts.user')
@section('title', 'Főoldal')

@section('content')
  <h2>Jegy/bérlet vásárlás</h2>
  <p>Ezen az oldalon tudsz a kreditedből jegyeket és bérleteket vásárolni. Vigyázz, melyiket választod ki, mert a vásárlás
    után nem tudod már visszamondani a terméket.</p>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
      <h6 class="card-subtitle text-muted">elérhető jegyek/bérletek</h6>
    </div>
    <div class="card-body">
      <div class="card-text">
        <h5 class="mb-3">Elérhető kreditek: <b>{{ Auth::user()->credits }}</b></h5>

        <table class="table">
          <thead>
            <tr>
              <th>Név</th>
              <th>Típus</th>
              <th>Megjegyzés</th>
              <th>Elérhető</th>
              <th>Ár</th>
              <th>Vásárlás/hosszabbítás</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($gym->buyableTickets as $ticket)
              <tr>
                <td>{{ $ticket->name }}</td>
                <td>{{ $ticket->type }}</td>
                <td>{{ $ticket->description }}</td>
                @if ($ticket->quantity == 999)
                  <td>végtelen</td>
                @else
                  <td>{{ $ticket->quantity }}</td>
                @endif
                @if ($ticket->price == 0)
                  <td>ingyenes</td>
                @else
                  <td>{{ $ticket->price }}</td>
                @endif
                @if (Auth::user()->tickets->where('type_id', $ticket->id)->count() > 0)
                  <td>
                    <button class="btn btn-primary">Hosszabbítás</button>
                  </td>
                @else
                  <td>
                    <button class="btn btn-success">Vásárlás</button>
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>

        <button type="button" class="btn btn-link">Edzőterem váltása</button>
      </div>
    </div>
  </div>
@endsection
